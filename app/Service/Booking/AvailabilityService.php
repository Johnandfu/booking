<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Booking\BookingBlackout;
use App\Model\Booking\BookingHoliday;
use App\Model\Booking\BookingLocation;
use App\Model\Booking\BookingSchedule;
use App\Model\Booking\BookingService;
use App\Model\Booking\BookingSlot;
use App\Model\Booking\BookingStaff;
use App\Model\Enums\Booking\SlotStatus;
use App\Model\Shop;
use App\Repository\Booking\BookingSlotRepository;
use Carbon\Carbon;

/**
 * 按店铺时区计算可预约 Slot，并落库 booking_slots。
 */
final class AvailabilityService
{
    public function __construct(
        private readonly BookingSlotRepository $slotRepository,
    ) {}

    /**
     * @return list<array{slot_id:int,start_at:string,end_at:string,capacity:int,booked:int,remaining:int,staff_id:int,location_id:int,service_id:int}>
     */
    public function listAvailableSlots(
        Shop $shop,
        int $serviceId,
        int $locationId,
        string $dateYmd,
        ?int $staffId = null,
    ): array {
        $service = BookingService::query()
            ->where('shop_id', $shop->id)
            ->whereKey($serviceId)
            ->where('status', BookingService::STATUS_ENABLED)
            ->first();
        if ($service === null) {
            throw new BusinessException(ResultCode::NOT_FOUND, 'Service not found');
        }

        $location = BookingLocation::query()
            ->where('shop_id', $shop->id)
            ->whereKey($locationId)
            ->where('status', BookingLocation::STATUS_ENABLED)
            ->first();
        if ($location === null) {
            throw new BusinessException(ResultCode::NOT_FOUND, 'Location not found');
        }

        $tz = $shop->timezone ?: 'UTC';
        try {
            $dayStart = Carbon::createFromFormat('Y-m-d', $dateYmd, $tz)->startOfDay();
        } catch (\Throwable) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Invalid date');
        }

        if (BookingHoliday::query()
            ->where('shop_id', $shop->id)
            ->whereDate('holiday_date', $dayStart->toDateString())
            ->where('status', 1)
            ->exists()) {
            return [];
        }

        $weekday = (int) $dayStart->dayOfWeek; // 0=Sun
        $staffQuery = BookingStaff::query()
            ->where('shop_id', $shop->id)
            ->where('status', BookingStaff::STATUS_ENABLED)
            ->whereHas('services', static fn ($q) => $q->where('booking_services.id', $serviceId))
            ->whereHas('locations', static fn ($q) => $q->where('booking_locations.id', $locationId));
        if ($staffId !== null) {
            $staffQuery->whereKey($staffId);
        }
        $staffList = $staffQuery->get();
        if ($staffList->isEmpty()) {
            return [];
        }

        $nowUtc = Carbon::now('UTC');
        $result = [];

        foreach ($staffList as $staff) {
            $schedules = BookingSchedule::query()
                ->where('shop_id', $shop->id)
                ->where('staff_id', $staff->id)
                ->where('weekday', $weekday)
                ->where('status', 1)
                ->where(static function ($q) use ($locationId) {
                    $q->whereNull('location_id')->orWhere('location_id', $locationId);
                })
                ->get();

            foreach ($schedules as $schedule) {
                $windowStart = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $dayStart->toDateString() . ' ' . $this->normalizeTime((string) $schedule->start_time),
                    $tz
                );
                $windowEnd = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $dayStart->toDateString() . ' ' . $this->normalizeTime((string) $schedule->end_time),
                    $tz
                );
                if ($windowEnd->lte($windowStart)) {
                    continue;
                }

                $step = max(1, (int) $service->duration_minutes + (int) $service->buffer_minutes);
                $cursor = $windowStart->copy();
                while ($cursor->copy()->addMinutes((int) $service->duration_minutes)->lte($windowEnd)) {
                    $slotStartLocal = $cursor->copy();
                    $slotEndLocal = $cursor->copy()->addMinutes((int) $service->duration_minutes);
                    $cursor->addMinutes($step);

                    $startUtc = $slotStartLocal->copy()->utc();
                    $endUtc = $slotEndLocal->copy()->utc();
                    if ($endUtc->lte($nowUtc)) {
                        continue;
                    }

                    if ($this->isBlackedOut((int) $shop->id, (int) $staff->id, $locationId, $startUtc, $endUtc)) {
                        continue;
                    }

                    $slot = $this->ensureSlot(
                        (int) $shop->id,
                        (int) $service->id,
                        $locationId,
                        (int) $staff->id,
                        $startUtc,
                        $endUtc,
                        (int) $service->capacity,
                    );

                    $activeLocks = $slot->locks()
                        ->where('status', 'active')
                        ->where('expire_at', '>', $nowUtc)
                        ->count();
                    $occupied = (int) $slot->booked + $activeLocks;
                    if ($slot->status === SlotStatus::Disabled->value || $occupied >= (int) $slot->capacity) {
                        continue;
                    }

                    $result[] = [
                        'slot_id' => (int) $slot->id,
                        'start_at' => $startUtc->toIso8601String(),
                        'end_at' => $endUtc->toIso8601String(),
                        'start_at_local' => $slotStartLocal->toDateTimeString(),
                        'end_at_local' => $slotEndLocal->toDateTimeString(),
                        'capacity' => (int) $slot->capacity,
                        'booked' => (int) $slot->booked,
                        'remaining' => (int) $slot->capacity - $occupied,
                        'staff_id' => (int) $staff->id,
                        'staff_name' => (string) $staff->name,
                        'location_id' => $locationId,
                        'service_id' => (int) $service->id,
                    ];
                }
            }
        }

        usort($result, static fn (array $a, array $b): int => strcmp($a['start_at'], $b['start_at']));

        return $result;
    }

    private function ensureSlot(
        int $shopId,
        int $serviceId,
        int $locationId,
        int $staffId,
        Carbon $startUtc,
        Carbon $endUtc,
        int $capacity,
    ): BookingSlot {
        $existing = BookingSlot::query()
            ->where('shop_id', $shopId)
            ->where('service_id', $serviceId)
            ->where('location_id', $locationId)
            ->where('staff_id', $staffId)
            ->where('start_at', $startUtc)
            ->first();
        if ($existing !== null) {
            return $existing;
        }

        /** @var BookingSlot $slot */
        $slot = $this->slotRepository->create([
            'shop_id' => $shopId,
            'service_id' => $serviceId,
            'location_id' => $locationId,
            'staff_id' => $staffId,
            'start_at' => $startUtc,
            'end_at' => $endUtc,
            'capacity' => $capacity,
            'booked' => 0,
            'status' => SlotStatus::Available->value,
        ]);

        return $slot;
    }

    private function isBlackedOut(int $shopId, int $staffId, int $locationId, Carbon $startUtc, Carbon $endUtc): bool
    {
        return BookingBlackout::query()
            ->where('shop_id', $shopId)
            ->where('start_at', '<', $endUtc)
            ->where('end_at', '>', $startUtc)
            ->where(static function ($q) use ($staffId) {
                $q->whereNull('staff_id')->orWhere('staff_id', $staffId);
            })
            ->where(static function ($q) use ($locationId) {
                $q->whereNull('location_id')->orWhere('location_id', $locationId);
            })
            ->exists();
    }

    private function normalizeTime(string $time): string
    {
        // MySQL time may be H:i:s
        if (preg_match('/^\d{2}:\d{2}$/', $time) === 1) {
            return $time . ':00';
        }

        return $time;
    }
}
