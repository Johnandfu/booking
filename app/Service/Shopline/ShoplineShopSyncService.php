<?php

declare(strict_types=1);

namespace App\Service\Shopline;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Booking\BookingService;
use App\Model\Shop;
use App\Repository\ShopRepository;
use App\Repository\ShopTokenRepository;
use App\Shopline\Client\ShoplineAdminClient;
use App\Shopline\Exception\ShoplineApiException;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Psr\Log\LoggerInterface;

/**
 * 店铺资料同步、预约服务 → SHOPLINE Product 创建。
 */
final class ShoplineShopSyncService
{
    private const LOCK_TTL = 30;

    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ShoplineAdminClient $adminClient,
        private readonly ShopTokenRepository $shopTokenRepository,
        private readonly ShopRepository $shopRepository,
        private readonly Redis $redis,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('shopline');
    }

    /**
     * 从 SHOPLINE 拉取 iana_timezone 写入 shops.timezone。
     * Scope: read_store_information
     *
     * @return array{timezone:string,domain:string,name:string}
     */
    public function syncTimezone(Shop $shop): array
    {
        $accessToken = $this->requireAccessToken($shop);
        $data = $this->adminClient->getShop((string) $shop->handle, $accessToken);
        $timezone = trim((string) ($data['iana_timezone'] ?? ''));
        if ($timezone === '') {
            throw new BusinessException(ResultCode::FAIL, 'Shop timezone missing from SHOPLINE');
        }

        $domain = trim((string) ($data['domain'] ?? $shop->domain));
        $this->shopRepository->updateById((int) $shop->id, [
            'timezone' => $timezone,
            'domain' => $domain !== '' ? $domain : $shop->domain,
            'locale' => trim((string) ($data['language'] ?? $shop->locale)),
        ]);

        return [
            'timezone' => $timezone,
            'domain' => (string) ($domain !== '' ? $domain : $shop->domain),
            'name' => (string) ($data['name'] ?? ''),
        ];
    }

    /**
     * 为预约服务创建 SHOPLINE 商品并回写 shopline_product_id。
     * Scope: write_products
     *
     * @return array{service_id:int,shopline_product_id:string,title:string}
     */
    public function syncServiceProduct(Shop $shop, int $serviceId, ?string $price = null): array
    {
        $lockKey = 'shopline:product:sync:' . $shop->id . ':' . $serviceId;
        if (! $this->redis->set($lockKey, '1', ['NX', 'EX' => self::LOCK_TTL])) {
            throw new BusinessException(ResultCode::TOO_MANY_REQUEST, 'Product sync is processing');
        }

        try {
            /** @var null|BookingService $service */
            $service = BookingService::query()
                ->where('shop_id', $shop->id)
                ->whereKey($serviceId)
                ->first();
            if ($service === null) {
                throw new BusinessException(ResultCode::NOT_FOUND, 'Service not found');
            }
            if ($service->shopline_product_id !== '') {
                return [
                    'service_id' => (int) $service->id,
                    'shopline_product_id' => (string) $service->shopline_product_id,
                    'title' => (string) $service->name,
                ];
            }

            $accessToken = $this->requireAccessToken($shop);
            $productPayload = [
                'title' => (string) $service->name,
                'body_html' => (string) ($service->description ?: $service->name),
                'status' => 'active',
                'tags' => ['booking', 'calendar-booking'],
                'variants' => [
                    [
                        'price' => $this->normalizePrice($price),
                        'required_shipping' => false,
                        'sku' => 'BOOKING-SVC-' . $service->id,
                    ],
                ],
            ];

            try {
                $created = $this->adminClient->createProduct(
                    (string) $shop->handle,
                    $accessToken,
                    $productPayload
                );
            } catch (ShoplineApiException $e) {
                $this->logger->warning('Create SHOPLINE product failed', [
                    'shop_id' => $shop->id,
                    'service_id' => $serviceId,
                    'message' => $e->getMessage(),
                ]);
                throw new BusinessException(ResultCode::FAIL, 'Failed to create SHOPLINE product');
            }

            $productId = (string) $created['id'];
            $service->update(['shopline_product_id' => $productId]);

            return [
                'service_id' => (int) $service->id,
                'shopline_product_id' => $productId,
                'title' => (string) ($created['title'] ?? $service->name),
            ];
        } finally {
            $this->redis->del($lockKey);
        }
    }

    private function requireAccessToken(Shop $shop): string
    {
        $token = $this->shopTokenRepository->findByShopId((int) $shop->id);
        $accessToken = $token?->getAttribute('access_token');
        if ($token === null || ! is_string($accessToken) || $accessToken === '') {
            throw new BusinessException(ResultCode::FAIL, 'Shop access token missing');
        }

        return $accessToken;
    }

    private function normalizePrice(?string $price): string
    {
        if ($price === null || trim($price) === '') {
            return '0.00';
        }
        if (! is_numeric($price)) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Invalid price');
        }

        return number_format((float) $price, 2, '.', '');
    }
}
