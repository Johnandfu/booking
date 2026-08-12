<?php

declare(strict_types=1);

namespace HyperfTests\Feature\Shopline;

use App\Model\Booking\Booking;
use App\Model\Booking\BookingSlot;
use App\Model\Booking\WebhookEvent;
use App\Model\Enums\Booking\BookingStatus;
use App\Model\Enums\Booking\SlotStatus;
use App\Model\Shop;
use App\Service\Shopline\ShoplineWebhookService;
use Carbon\Carbon;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use HyperfTests\HttpTestCase;

/**
 * Webhook 重复投递与订单确认（需要 DB + Redis）。
 *
 * @internal
 * @coversNothing
 */
final class ShoplineWebhookServiceTest extends HttpTestCase
{
    private Shop $shop;

    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        ApplicationContext::getContainer()->get(ConfigInterface::class)
            ->set('shopline.app_secret', 'test-webhook-secret');

        WebhookEvent::query()->where('event_id', 'evt-webhook-1')->delete();
        Booking::query()->where('booking_no', 'BTESTWEBHOOK001')->delete();
        Shop::query()->where('handle', 'webhook-shop')->delete();

        $this->shop = Shop::query()->create([
            'handle' => 'webhook-shop',
            'domain' => 'webhook-shop.myshopline.com',
            'timezone' => 'UTC',
            'locale' => 'en',
            'status' => Shop::STATUS_INSTALLED,
            'installed_at' => Carbon::now('UTC'),
        ]);

        $slot = BookingSlot::query()->create([
            'shop_id' => $this->shop->id,
            'service_id' => 1,
            'location_id' => 1,
            'staff_id' => 1,
            'start_at' => Carbon::now('UTC')->addDay(),
            'end_at' => Carbon::now('UTC')->addDay()->addHour(),
            'capacity' => 1,
            'booked' => 1,
            'status' => SlotStatus::Full->value,
        ]);

        $this->booking = Booking::query()->create([
            'shop_id' => $this->shop->id,
            'booking_no' => 'BTESTWEBHOOK001',
            'status' => BookingStatus::Pending->value,
            'customer_name' => 'Ada',
            'customer_email' => 'ada@example.com',
            'customer_phone' => '',
            'service_id' => 1,
            'location_id' => 1,
            'staff_id' => 1,
            'slot_id' => $slot->id,
            'start_at' => $slot->start_at,
            'end_at' => $slot->end_at,
            'lock_token' => 'lock-test',
            'idempotency_key' => null,
            'shopline_order_id' => '',
            'remark' => '',
        ]);
    }

    public function testOrdersCreateConfirmsBookingIdempotently(): void
    {
        /** @var ShoplineWebhookService $service */
        $service = ApplicationContext::getContainer()->get(ShoplineWebhookService::class);
        $body = json_encode([
            'id' => 'SLORDER1001',
            'note_attributes' => [
                ['name' => 'booking_no', 'value' => 'BTESTWEBHOOK001'],
            ],
        ], JSON_UNESCAPED_UNICODE);
        self::assertIsString($body);
        $hmac = hash_hmac('sha256', $body, 'test-webhook-secret');
        $headers = [
            'X-Shopline-Hmac-Sha256' => $hmac,
            'X-Shopline-Topic' => 'orders/create',
            'X-Shopline-Webhook-Id' => 'evt-webhook-1',
            'X-Shopline-Shop-Domain' => 'webhook-shop.myshopline.com',
        ];

        $service->handle($body, $headers);
        $this->booking->refresh();
        self::assertSame(BookingStatus::Confirmed->value, $this->booking->status);
        self::assertSame('SLORDER1001', $this->booking->shopline_order_id);

        $service->handle($body, $headers);
        $this->booking->refresh();
        self::assertSame(BookingStatus::Confirmed->value, $this->booking->status);
        self::assertSame(1, WebhookEvent::query()->where('event_id', 'evt-webhook-1')->count());
    }
}
