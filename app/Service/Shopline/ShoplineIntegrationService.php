<?php

declare(strict_types=1);

namespace App\Service\Shopline;

use App\Model\Shop;
use App\Model\ShopToken;
use App\Repository\ShopTokenRepository;
use App\Shopline\Client\ShoplineAdminClient;
use App\Shopline\Exception\ShoplineApiException;
use Carbon\Carbon;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * OAuth 后订阅 Webhook、Token 到期刷新。
 */
final class ShoplineIntegrationService
{
    public const TOPIC_ORDERS_CREATE = 'orders/create';

    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ShoplineAdminClient $adminClient,
        private readonly ShoplineOAuthService $oauthService,
        private readonly ShoplineShopSyncService $shopSyncService,
        private readonly ShopTokenRepository $shopTokenRepository,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('shopline');
    }

    /**
     * 安装成功后订阅 orders/create（失败不阻断安装）。
     */
    public function ensureOrderCreatedWebhook(Shop $shop): void
    {
        $address = (string) config('shopline.webhook_callback_url');
        if ($address === '') {
            $appUrl = rtrim((string) env('APP_URL', ''), '/');
            if ($appUrl === '') {
                $this->logger->warning('Webhook callback URL not configured, skip subscribe');
                return;
            }
            $address = $appUrl . '/shopline/webhook';
        }

        $token = $this->shopTokenRepository->findByShopId((int) $shop->id);
        $accessToken = $token?->getAttribute('access_token');
        if ($token === null || ! is_string($accessToken) || $accessToken === '') {
            $this->logger->warning('No access token for webhook subscribe', ['shop_id' => $shop->id]);
            return;
        }

        try {
            $this->adminClient->subscribeWebhook(
                (string) $shop->handle,
                $accessToken,
                self::TOPIC_ORDERS_CREATE,
                $address,
                (string) config('shopline.webhook_api_version', 'v20240601'),
            );
        } catch (ShoplineApiException $e) {
            $this->logger->warning('Subscribe orders/create webhook failed', [
                'shop_id' => $shop->id,
                'handle' => $shop->handle,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 安装后同步店铺时区（失败不阻断安装）。
     */
    public function syncShopTimezoneQuietly(Shop $shop): void
    {
        try {
            $this->shopSyncService->syncTimezone($shop);
        } catch (Throwable $e) {
            $this->logger->warning('Sync shop timezone failed', [
                'shop_id' => $shop->id,
                'handle' => $shop->handle,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 刷新即将过期的 access token。
     *
     * @return array{refreshed:int,failed:int,skipped:int}
     */
    public function refreshExpiringTokens(?int $withinMinutes = null): array
    {
        $minutes = $withinMinutes ?? (int) config('shopline.token_refresh_before_minutes', 60);
        $deadline = Carbon::now('UTC')->addMinutes(max(1, $minutes));

        $tokens = ShopToken::query()
            ->whereNotNull('expire_at')
            ->where('expire_at', '<=', $deadline)
            ->orderBy('expire_at')
            ->limit(100)
            ->get();

        $refreshed = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($tokens as $token) {
            $shop = Shop::query()->whereKey($token->shop_id)->first();
            if ($shop === null || (int) $shop->status !== Shop::STATUS_INSTALLED) {
                ++$skipped;
                continue;
            }

            try {
                $this->oauthService->refreshShopToken($shop);
                ++$refreshed;
            } catch (Throwable $e) {
                ++$failed;
                $this->logger->warning('Token refresh failed', [
                    'shop_id' => $shop->id,
                    'handle' => $shop->handle,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return compact('refreshed', 'failed', 'skipped');
    }
}
