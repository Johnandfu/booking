<?php

declare(strict_types=1);

namespace App\Service\Shopline;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Shop;
use App\Repository\ShopRepository;
use App\Repository\ShopTokenRepository;
use App\Shopline\Client\ShoplineOAuthClient;
use App\Shopline\Exception\ShoplineApiException;
use App\Shopline\Exception\ShoplineSignatureException;
use App\Shopline\ShoplineSignature;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Psr\Log\LoggerInterface;

/**
 * Shop 安装 / OAuth 回调业务（Controller 不直接调 SHOPLINE API）。
 *
 * 内嵌应用授权须用 App Bridge Oauth.invoke，禁止服务端 302 到授权页（会嵌在 iframe 内）。
 * 文档：https://developer.shopline.com/docs/apps/development-tool/app-bridge/actions/authorization
 */
final class ShoplineOAuthService
{
    private const LOCK_TTL_SECONDS = 30;

    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ShoplineSignature $signature,
        private readonly ShoplineOAuthClient $oauthClient,
        private readonly ShopRepository $shopRepository,
        private readonly ShopTokenRepository $shopTokenRepository,
        private readonly Redis $redis,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('shopline');
    }

    /**
     * 内嵌 App URL 入口：验签并判断是否已授权。
     *
     * @param array<string, mixed> $query
     * @return array{
     *   authorized: bool,
     *   handle: string,
     *   host: string,
     *   locale: string,
     *   app_key: string,
     *   scopes: string,
     *   redirect_uri: string,
     *   shop_id: null|int
     * }
     */
    public function resolveEmbeddedEntry(array $query): array
    {
        $this->assertSignedGet($query);

        $handle = trim((string) ($query['handle'] ?? ''));
        if ($handle === '') {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Missing handle');
        }

        $redirectUri = (string) config('shopline.oauth_redirect_uri');
        $scopes = (string) config('shopline.scopes');
        $appKey = (string) config('shopline.app_key');
        if ($redirectUri === '' || $scopes === '' || $appKey === '') {
            throw new BusinessException(ResultCode::FAIL, 'SHOPLINE OAuth is not fully configured');
        }

        $shop = $this->shopRepository->findByHandle($handle);
        $authorized = $shop !== null && $this->hasValidToken((int) $shop->id);

        return [
            'authorized' => $authorized,
            'handle' => $handle,
            'host' => $handle . '.myshopline.com',
            'locale' => trim((string) ($query['lang'] ?? ($shop->locale ?? ''))),
            'app_key' => $appKey,
            'scopes' => $scopes,
            'redirect_uri' => $redirectUri,
            'shop_id' => $shop?->id !== null ? (int) $shop->id : null,
        ];
    }

    /**
     * 处理 OAuth 回调：换 token、幂等写入 shops / shop_tokens。
     *
     * @param array<string, mixed> $query
     * @return array{shop_id:int,handle:string,host:string}
     */
    public function handleOAuthCallback(array $query): array
    {
        $this->assertSignedGet($query);

        $handle = trim((string) ($query['handle'] ?? ''));
        $code = trim((string) ($query['code'] ?? ''));
        $locale = trim((string) ($query['lang'] ?? ''));

        if ($handle === '' || $code === '') {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Missing handle or code');
        }

        $lockKey = 'shopline:oauth:callback:' . $handle;
        if (! $this->redis->set($lockKey, '1', ['NX', 'EX' => self::LOCK_TTL_SECONDS])) {
            throw new BusinessException(ResultCode::TOO_MANY_REQUEST, 'OAuth callback is processing');
        }

        try {
            try {
                $tokenResult = $this->oauthClient->createAccessToken($handle, $code);
            } catch (ShoplineApiException $e) {
                $this->logger->warning('SHOPLINE OAuth create token failed', [
                    'handle' => $handle,
                    'i18nCode' => $e->i18nCode,
                    'message' => $e->getMessage(),
                ]);
                throw new BusinessException(ResultCode::FAIL, 'Failed to create access token');
            }

            $expireAt = Carbon::parse($tokenResult->expireTime)->utc();
            $now = Carbon::now('UTC');

            /** @var array{shop_id:int,handle:string,host:string} $result */
            $result = Db::transaction(function () use ($handle, $locale, $tokenResult, $expireAt, $now) {
                $shop = $this->shopRepository->findByHandle($handle);
                if ($shop === null) {
                    $shop = $this->shopRepository->create([
                        'handle' => $handle,
                        'domain' => $handle . '.myshopline.com',
                        'timezone' => (string) config('shopline.default_timezone', 'UTC'),
                        'locale' => $locale,
                        'status' => Shop::STATUS_INSTALLED,
                        'installed_at' => $now,
                    ]);
                } else {
                    $this->shopRepository->updateById($shop->id, [
                        'domain' => $handle . '.myshopline.com',
                        'locale' => $locale !== '' ? $locale : $shop->locale,
                        'status' => Shop::STATUS_INSTALLED,
                        'installed_at' => $shop->installed_at ?? $now,
                    ]);
                    $shop = $this->shopRepository->findById($shop->id);
                }

                if ($shop === null) {
                    throw new BusinessException(ResultCode::FAIL, 'Failed to persist shop');
                }

                $existing = $this->shopTokenRepository->findByShopId((int) $shop->id);
                $tokenPayload = [
                    'access_token' => $tokenResult->accessToken,
                    'scope' => $tokenResult->scope,
                    'expire_at' => $expireAt,
                ];

                if ($existing === null) {
                    $this->shopTokenRepository->create([
                        'shop_id' => (int) $shop->id,
                        ...$tokenPayload,
                    ]);
                } else {
                    $this->shopTokenRepository->updateById($existing->id, $tokenPayload);
                }

                return [
                    'shop_id' => (int) $shop->id,
                    'handle' => (string) $shop->handle,
                    'host' => (string) $shop->handle . '.myshopline.com',
                ];
            });

            return $result;
        } finally {
            $this->redis->del($lockKey);
        }
    }

    /**
     * 刷新即将过期的 token（供后续 Job / Phase 调用）。
     */
    public function refreshShopToken(Shop $shop): void
    {
        $lockKey = 'shopline:oauth:refresh:' . $shop->handle;
        if (! $this->redis->set($lockKey, '1', ['NX', 'EX' => self::LOCK_TTL_SECONDS])) {
            throw new BusinessException(ResultCode::TOO_MANY_REQUEST, 'Token refresh is processing');
        }

        try {
            try {
                $tokenResult = $this->oauthClient->refreshAccessToken($shop->handle);
            } catch (ShoplineApiException $e) {
                $this->logger->warning('SHOPLINE OAuth refresh token failed', [
                    'handle' => $shop->handle,
                    'i18nCode' => $e->i18nCode,
                    'message' => $e->getMessage(),
                ]);
                throw new BusinessException(ResultCode::FAIL, 'Failed to refresh access token');
            }

            $expireAt = Carbon::parse($tokenResult->expireTime)->utc();
            $existing = $this->shopTokenRepository->findByShopId((int) $shop->id);
            if ($existing === null) {
                $this->shopTokenRepository->create([
                    'shop_id' => (int) $shop->id,
                    'access_token' => $tokenResult->accessToken,
                    'scope' => $tokenResult->scope,
                    'expire_at' => $expireAt,
                ]);
                return;
            }

            $this->shopTokenRepository->updateById($existing->id, [
                'access_token' => $tokenResult->accessToken,
                'scope' => $tokenResult->scope,
                'expire_at' => $expireAt,
            ]);
        } finally {
            $this->redis->del($lockKey);
        }
    }

    private function hasValidToken(int $shopId): bool
    {
        $token = $this->shopTokenRepository->findByShopId($shopId);
        if ($token === null || $token->expire_at === null) {
            return false;
        }

        return $token->expire_at->gt(Carbon::now('UTC'));
    }

    /**
     * @param array<string, mixed> $query
     */
    private function assertSignedGet(array $query): void
    {
        $appKey = (string) config('shopline.app_key');
        $appSecret = (string) config('shopline.app_secret');
        $sign = (string) ($query['sign'] ?? '');
        $timestamp = (string) ($query['timestamp'] ?? '');
        $incomingAppKey = (string) ($query['appkey'] ?? $query['appKey'] ?? '');

        if ($appKey === '' || $appSecret === '') {
            throw new BusinessException(ResultCode::FAIL, 'SHOPLINE app credentials are not configured');
        }

        if ($incomingAppKey !== '' && ! hash_equals($appKey, $incomingAppKey)) {
            throw new ShoplineSignatureException('Invalid appkey');
        }

        if (! $this->signature->isTimestampFresh($timestamp)) {
            throw new ShoplineSignatureException('Timestamp expired');
        }

        if (! $this->signature->verifyGet($query, $appSecret, $sign)) {
            throw new ShoplineSignatureException('Invalid signature');
        }
    }
}
