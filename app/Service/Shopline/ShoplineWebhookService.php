<?php

declare(strict_types=1);

namespace App\Service\Shopline;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Booking\WebhookEvent;
use App\Model\Shop;
use App\Repository\Booking\WebhookEventRepository;
use App\Repository\ShopRepository;
use App\Service\Booking\BookingFlowService;
use App\Shopline\Exception\ShoplineSignatureException;
use App\Shopline\ShoplineSignature;
use Carbon\Carbon;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Webhook 验签、落库、幂等处理。
 *
 * Overview: https://developer.shopline.com/docs/apps/api-instructions-for-use/webhooks/overview
 * orders/create: https://developer.shopline.com/docs/webhook/order/order-created
 */
final class ShoplineWebhookService
{
    private const LOCK_TTL = 30;

    public const TOPIC_ORDERS_CREATE = 'orders/create';

    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ShoplineSignature $signature,
        private readonly ShopRepository $shopRepository,
        private readonly WebhookEventRepository $webhookEventRepository,
        private readonly BookingFlowService $bookingFlowService,
        private readonly Redis $redis,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('shopline');
    }

    /**
     * @param array<string, string> $headers
     */
    public function handle(string $rawBody, array $headers): void
    {
        $secret = (string) config('shopline.app_secret');
        $hmac = $this->header($headers, 'X-Shopline-Hmac-Sha256');
        if (! $this->signature->verifyWebhook($rawBody, $hmac, $secret)) {
            throw new ShoplineSignatureException('Invalid webhook signature');
        }

        $topic = $this->header($headers, 'X-Shopline-Topic');
        $eventId = $this->header($headers, 'X-Shopline-Webhook-Id');
        $shopDomain = $this->header($headers, 'X-Shopline-Shop-Domain');
        if ($topic === '' || $eventId === '') {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Missing webhook topic or id');
        }

        $payload = json_decode($rawBody, true);
        if (! is_array($payload)) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Invalid webhook JSON body');
        }

        $shop = $this->resolveShop($shopDomain);
        $lockKey = 'shopline:webhook:' . $eventId;
        if (! $this->redis->set($lockKey, '1', ['NX', 'EX' => self::LOCK_TTL])) {
            return;
        }

        try {
            $existing = $this->webhookEventRepository->findByEventId($eventId);
            if ($existing !== null && $existing->status === WebhookEvent::STATUS_PROCESSED) {
                return;
            }

            $event = $existing;
            if ($event === null) {
                try {
                    $event = $this->webhookEventRepository->create([
                        'shop_id' => $shop?->id,
                        'topic' => $topic,
                        'event_id' => $eventId,
                        'payload' => $payload,
                        'status' => WebhookEvent::STATUS_PENDING,
                    ]);
                } catch (Throwable) {
                    $event = $this->webhookEventRepository->findByEventId($eventId);
                    if ($event !== null && $event->status === WebhookEvent::STATUS_PROCESSED) {
                        return;
                    }
                    if ($event === null) {
                        throw new BusinessException(ResultCode::FAIL, 'Failed to persist webhook event');
                    }
                }
            }

            $event->update(['status' => WebhookEvent::STATUS_PROCESSING]);

            try {
                $this->dispatch($topic, $shop, $payload);
                $event->update([
                    'status' => WebhookEvent::STATUS_PROCESSED,
                    'processed_at' => Carbon::now('UTC'),
                    'shop_id' => $shop?->id ?? $event->shop_id,
                ]);
            } catch (Throwable $e) {
                $event->update(['status' => WebhookEvent::STATUS_FAILED]);
                $this->logger->warning('Webhook processing failed', [
                    'event_id' => $eventId,
                    'topic' => $topic,
                    'message' => $e->getMessage(),
                ]);
                throw $e;
            }
        } finally {
            $this->redis->del($lockKey);
        }
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function dispatch(string $topic, ?Shop $shop, array $payload): void
    {
        if ($topic !== self::TOPIC_ORDERS_CREATE) {
            return;
        }
        if ($shop === null) {
            throw new BusinessException(ResultCode::NOT_FOUND, 'Shop not found for webhook');
        }

        $orderId = (string) ($payload['id'] ?? '');
        if ($orderId === '') {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Missing order id');
        }

        $bookingNo = $this->extractBookingNo($payload);
        if ($bookingNo === '') {
            // 无预约关联的普通订单：记录后忽略（仍标记 processed）
            $this->logger->info('orders/create without booking_no, skip confirm', [
                'shop_id' => $shop->id,
                'order_id' => $orderId,
            ]);
            return;
        }

        $this->bookingFlowService->confirmByOrder($shop, $bookingNo, $orderId);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function extractBookingNo(array $payload): string
    {
        $attrs = $payload['note_attributes'] ?? null;
        if (is_array($attrs)) {
            foreach ($attrs as $attr) {
                if (! is_array($attr)) {
                    continue;
                }
                $name = strtolower(trim((string) ($attr['name'] ?? $attr['key'] ?? '')));
                $value = trim((string) ($attr['value'] ?? ''));
                if ($value !== '' && in_array($name, ['booking_no', 'booking-no', 'bookingno'], true)) {
                    return $value;
                }
            }
        }

        $note = trim((string) ($payload['note'] ?? ''));
        if ($note !== '' && preg_match('/booking_no\s*[:=]\s*([A-Za-z0-9_-]+)/i', $note, $m) === 1) {
            return $m[1];
        }

        return '';
    }

    private function resolveShop(string $shopDomain): ?Shop
    {
        $domain = strtolower(trim($shopDomain));
        if ($domain === '') {
            return null;
        }
        $handle = explode('.', $domain)[0] ?? '';
        if ($handle === '') {
            return null;
        }

        return $this->shopRepository->findByHandle($handle);
    }

    /**
     * @param array<string, string> $headers
     */
    private function header(array $headers, string $name): string
    {
        foreach ($headers as $key => $value) {
            if (strcasecmp((string) $key, $name) === 0) {
                return trim((string) $value);
            }
        }

        return '';
    }
}
