<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Booking\Booking;
use App\Model\Booking\BookingItem;
use App\Model\Booking\BookingService;
use App\Model\Booking\BookingSlot;
use App\Model\Booking\BookingSlotLock;
use App\Model\Enums\Booking\BookingStatus;
use App\Model\Enums\Booking\SlotLockStatus;
use App\Model\Enums\Booking\SlotStatus;
use App\Model\Shop;
use App\Repository\Booking\BookingRepository;
use App\Repository\Booking\BookingSlotLockRepository;
use App\Repository\Booking\BookingSlotRepository;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Redis\Redis;
use Hyperf\Stringable\Str;

/**
 * Slot 锁定与预约创建/取消（Transaction + SELECT FOR UPDATE + Redis 临时锁）。
 */
final class BookingFlowService
{
    private const REDIS_LOCK_TTL = 15;

    private const SLOT_LOCK_TTL_SECONDS = 600;

    public function __construct(
        private readonly BookingSlotRepository $slotRepository,
        private readonly BookingSlotLockRepository $slotLockRepository,
        private readonly BookingRepository $bookingRepository,
        private readonly Redis $redis,
    ) {}

    /**
     * @return array{lock_token:string,expire_at:string,slot_id:int}
     */
    public function lockSlot(Shop $shop, int $slotId, string $customerKey = ''): array
    {
        $redisKey = 'booking:slot:lock:' . $slotId;
        if (! $this->redis->set($redisKey, '1', ['NX', 'EX' => self::REDIS_LOCK_TTL])) {
            throw new BusinessException(ResultCode::TOO_MANY_REQUEST, 'Slot is busy');
        }

        try {
            return Db::transaction(function () use ($shop, $slotId, $customerKey) {
                /** @var null|BookingSlot $slot */
                $slot = BookingSlot::query()->whereKey($slotId)->where('shop_id', $shop->id)->lockForUpdate()->first();
                if ($slot === null) {
                    throw new BusinessException(ResultCode::NOT_FOUND, 'Slot not found');
                }
                $this->assertSlotBookable($slot);

                $token = Str::lower(Str::random(32));
                $expireAt = Carbon::now('UTC')->addSeconds(self::SLOT_LOCK_TTL_SECONDS);
                $this->slotLockRepository->create([
                    'shop_id' => (int) $shop->id,
                    'slot_id' => (int) $slot->id,
                    'lock_token' => $token,
                    'customer_key' => $customerKey,
                    'expire_at' => $expireAt,
                    'status' => SlotLockStatus::Active->value,
                ]);

                return [
                    'lock_token' => $token,
                    'expire_at' => $expireAt->toIso8601String(),
                    'slot_id' => (int) $slot->id,
                ];
            });
        } finally {
            $this->redis->del($redisKey);
        }
    }

    /**
     * @param array{
     *   slot_id:int,
     *   lock_token:string,
     *   customer_name?:string,
     *   customer_email?:string,
     *   customer_phone?:string,
     *   remark?:string,
     *   idempotency_key?:string,
     *   confirm?:bool
     * } $payload
     * @return array{booking_id:int,booking_no:string,status:string}
     */
    public function createBooking(Shop $shop, array $payload): array
    {
        $slotId = (int) ($payload['slot_id'] ?? 0);
        $lockToken = trim((string) ($payload['lock_token'] ?? ''));
        $idempotencyKey = trim((string) ($payload['idempotency_key'] ?? ''));
        if ($slotId <= 0 || $lockToken === '') {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'slot_id and lock_token are required');
        }

        if ($idempotencyKey !== '') {
            $existing = Booking::query()
                ->where('shop_id', $shop->id)
                ->where('idempotency_key', $idempotencyKey)
                ->first();
            if ($existing !== null) {
                return [
                    'booking_id' => (int) $existing->id,
                    'booking_no' => (string) $existing->booking_no,
                    'status' => (string) $existing->status,
                ];
            }
        }

        $redisKey = 'booking:slot:lock:' . $slotId;
        if (! $this->redis->set($redisKey, '1', ['NX', 'EX' => self::REDIS_LOCK_TTL])) {
            throw new BusinessException(ResultCode::TOO_MANY_REQUEST, 'Slot is busy');
        }

        try {
            return Db::transaction(function () use ($shop, $payload, $slotId, $lockToken, $idempotencyKey) {
                /** @var null|BookingSlot $slot */
                $slot = BookingSlot::query()->whereKey($slotId)->where('shop_id', $shop->id)->lockForUpdate()->first();
                if ($slot === null) {
                    throw new BusinessException(ResultCode::NOT_FOUND, 'Slot not found');
                }

                /** @var null|BookingSlotLock $lock */
                $lock = BookingSlotLock::query()
                    ->where('shop_id', $shop->id)
                    ->where('slot_id', $slotId)
                    ->where('lock_token', $lockToken)
                    ->lockForUpdate()
                    ->first();
                if ($lock === null || $lock->status !== SlotLockStatus::Active->value) {
                    throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Invalid lock token');
                }
                if ($lock->expire_at === null || $lock->expire_at->lte(Carbon::now('UTC'))) {
                    $lock->update(['status' => SlotLockStatus::Expired->value]);
                    throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Lock expired');
                }

                $this->assertSlotBookable($slot);

                $status = ! empty($payload['confirm'])
                    ? BookingStatus::Confirmed->value
                    : BookingStatus::Pending->value;

                $bookingNo = $this->generateBookingNo((int) $shop->id);
                /** @var Booking $booking */
                $booking = $this->bookingRepository->create([
                    'shop_id' => (int) $shop->id,
                    'booking_no' => $bookingNo,
                    'status' => $status,
                    'customer_name' => (string) ($payload['customer_name'] ?? ''),
                    'customer_email' => (string) ($payload['customer_email'] ?? ''),
                    'customer_phone' => (string) ($payload['customer_phone'] ?? ''),
                    'service_id' => (int) $slot->service_id,
                    'location_id' => (int) $slot->location_id,
                    'staff_id' => (int) $slot->staff_id,
                    'slot_id' => (int) $slot->id,
                    'start_at' => $slot->start_at,
                    'end_at' => $slot->end_at,
                    'lock_token' => $lockToken,
                    'idempotency_key' => $idempotencyKey !== '' ? $idempotencyKey : null,
                    'remark' => (string) ($payload['remark'] ?? ''),
                ]);

                $serviceTitle = (string) (BookingService::query()->whereKey($slot->service_id)->value('name') ?: 'Service');
                BookingItem::query()->create([
                    'booking_id' => (int) $booking->id,
                    'service_id' => (int) $slot->service_id,
                    'title' => $serviceTitle,
                    'quantity' => 1,
                    'meta' => null,
                ]);

                $booked = (int) $slot->booked + 1;
                $slotUpdate = ['booked' => $booked];
                if ($booked >= (int) $slot->capacity) {
                    $slotUpdate['status'] = SlotStatus::Full->value;
                }
                $slot->update($slotUpdate);
                $lock->update(['status' => SlotLockStatus::Consumed->value]);

                return [
                    'booking_id' => (int) $booking->id,
                    'booking_no' => (string) $booking->booking_no,
                    'status' => (string) $booking->status,
                ];
            });
        } finally {
            $this->redis->del($redisKey);
        }
    }

    /**
     * @return array{booking_id:int,status:string}
     */
    public function cancelBooking(Shop $shop, int $bookingId): array
    {
        return Db::transaction(function () use ($shop, $bookingId) {
            /** @var null|Booking $booking */
            $booking = Booking::query()->where('shop_id', $shop->id)->whereKey($bookingId)->lockForUpdate()->first();
            if ($booking === null) {
                throw new BusinessException(ResultCode::NOT_FOUND, 'Booking not found');
            }
            if (! in_array($booking->status, [BookingStatus::Pending->value, BookingStatus::Confirmed->value], true)) {
                throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Booking cannot be cancelled');
            }

            /** @var null|BookingSlot $slot */
            $slot = BookingSlot::query()->whereKey($booking->slot_id)->lockForUpdate()->first();
            if ($slot !== null) {
                $booked = max(0, (int) $slot->booked - 1);
                $update = ['booked' => $booked];
                if ($slot->status === SlotStatus::Full->value && $booked < (int) $slot->capacity) {
                    $update['status'] = SlotStatus::Available->value;
                }
                $slot->update($update);
            }

            $booking->update([
                'status' => BookingStatus::Cancelled->value,
                'cancelled_at' => Carbon::now('UTC'),
            ]);

            return [
                'booking_id' => (int) $booking->id,
                'status' => BookingStatus::Cancelled->value,
            ];
        });
    }

    /**
     * orders/create Webhook：按 booking_no 确认 pending 预约（幂等）。
     *
     * @return array{booking_id:int,status:string,shopline_order_id:string}
     */
    public function confirmByOrder(Shop $shop, string $bookingNo, string $shoplineOrderId): array
    {
        $redisKey = 'booking:confirm:order:' . $shop->id . ':' . $bookingNo;
        if (! $this->redis->set($redisKey, '1', ['NX', 'EX' => self::REDIS_LOCK_TTL])) {
            throw new BusinessException(ResultCode::TOO_MANY_REQUEST, 'Booking confirm is processing');
        }

        try {
            return Db::transaction(function () use ($shop, $bookingNo, $shoplineOrderId) {
                /** @var null|Booking $booking */
                $booking = Booking::query()
                    ->where('shop_id', $shop->id)
                    ->where('booking_no', $bookingNo)
                    ->lockForUpdate()
                    ->first();
                if ($booking === null) {
                    throw new BusinessException(ResultCode::NOT_FOUND, 'Booking not found for order');
                }

                if ($booking->status === BookingStatus::Confirmed->value) {
                    if ($booking->shopline_order_id === '' && $shoplineOrderId !== '') {
                        $booking->update(['shopline_order_id' => $shoplineOrderId]);
                    }
                    return [
                        'booking_id' => (int) $booking->id,
                        'status' => BookingStatus::Confirmed->value,
                        'shopline_order_id' => (string) $booking->shopline_order_id,
                    ];
                }

                if ($booking->status !== BookingStatus::Pending->value) {
                    throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Booking cannot be confirmed');
                }

                $booking->update([
                    'status' => BookingStatus::Confirmed->value,
                    'shopline_order_id' => $shoplineOrderId,
                ]);

                return [
                    'booking_id' => (int) $booking->id,
                    'status' => BookingStatus::Confirmed->value,
                    'shopline_order_id' => $shoplineOrderId,
                ];
            });
        } finally {
            $this->redis->del($redisKey);
        }
    }

    private function assertSlotBookable(BookingSlot $slot): void
    {
        if ($slot->status === SlotStatus::Disabled->value) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Slot disabled');
        }
        $now = Carbon::now('UTC');
        $activeLocks = BookingSlotLock::query()
            ->where('slot_id', $slot->id)
            ->where('status', SlotLockStatus::Active->value)
            ->where('expire_at', '>', $now)
            ->count();
        if (((int) $slot->booked + $activeLocks) >= (int) $slot->capacity) {
            throw new BusinessException(ResultCode::UNPROCESSABLE_ENTITY, 'Slot is full');
        }
    }

    private function generateBookingNo(int $shopId): string
    {
        return sprintf('B%d%s%s', $shopId, Carbon::now('UTC')->format('ymdHis'), Str::upper(Str::random(4)));
    }
}
