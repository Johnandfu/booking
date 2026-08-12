<?php

declare(strict_types=1);

namespace HyperfTests\Feature\Booking;

use App\Model\Booking\Booking;
use App\Model\Booking\BookingLocation;
use App\Model\Booking\BookingSchedule;
use App\Model\Booking\BookingService;
use App\Model\Booking\BookingSlot;
use App\Model\Booking\BookingStaff;
use App\Model\Shop;
use App\Service\Booking\AvailabilityService;
use App\Service\Booking\BookingFlowService;
use Carbon\Carbon;
use Hyperf\Context\ApplicationContext;
use HyperfTests\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
final class BookingFlowTest extends HttpTestCase
{
    private Shop $shop;

    protected function setUp(): void
    {
        parent::setUp();
        Booking::query()->delete();
        BookingSlot::query()->delete();
        BookingSchedule::query()->delete();
        BookingStaff::query()->delete();
        BookingLocation::query()->delete();
        BookingService::query()->delete();
        Shop::query()->where('handle', 'flow-shop')->delete();

        $this->shop = Shop::query()->create([
            'handle' => 'flow-shop',
            'domain' => 'flow-shop.myshopline.com',
            'timezone' => 'UTC',
            'locale' => 'en',
            'status' => Shop::STATUS_INSTALLED,
            'installed_at' => Carbon::now('UTC'),
        ]);
    }

    public function testLockCreateAndCancelBooking(): void
    {
        $service = BookingService::query()->create([
            'shop_id' => $this->shop->id,
            'name' => 'Cut',
            'duration_minutes' => 60,
            'capacity' => 1,
            'buffer_minutes' => 0,
            'status' => 1,
            'sort' => 0,
        ]);
        $location = BookingLocation::query()->create([
            'shop_id' => $this->shop->id,
            'name' => 'Store',
            'status' => 1,
            'sort' => 0,
        ]);
        $staff = BookingStaff::query()->create([
            'shop_id' => $this->shop->id,
            'name' => 'Bob',
            'status' => 1,
            'sort' => 0,
        ]);
        $staff->services()->sync([$service->id]);
        $staff->locations()->sync([$location->id]);

        $weekday = Carbon::now('UTC')->addDay()->dayOfWeek;
        $date = Carbon::now('UTC')->addDay()->toDateString();
        BookingSchedule::query()->create([
            'shop_id' => $this->shop->id,
            'staff_id' => $staff->id,
            'location_id' => null,
            'weekday' => $weekday,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'status' => 1,
        ]);

        $container = ApplicationContext::getContainer();
        /** @var AvailabilityService $availability */
        $availability = $container->get(AvailabilityService::class);
        /** @var BookingFlowService $flow */
        $flow = $container->get(BookingFlowService::class);

        $slots = $availability->listAvailableSlots($this->shop, (int) $service->id, (int) $location->id, $date);
        self::assertNotEmpty($slots);
        $slotId = $slots[0]['slot_id'];

        $lock = $flow->lockSlot($this->shop, $slotId, 'cust-1');
        $booking = $flow->createBooking($this->shop, [
            'slot_id' => $slotId,
            'lock_token' => $lock['lock_token'],
            'customer_name' => 'Ann',
            'idempotency_key' => 'idem-1',
            'confirm' => true,
        ]);
        self::assertSame('confirmed', $booking['status']);

        $again = $flow->createBooking($this->shop, [
            'slot_id' => $slotId,
            'lock_token' => $lock['lock_token'],
            'idempotency_key' => 'idem-1',
            'confirm' => true,
        ]);
        self::assertSame($booking['booking_id'], $again['booking_id']);

        $this->expectException(\App\Exception\BusinessException::class);
        $flow->lockSlot($this->shop, $slotId, 'cust-2');
    }
}
