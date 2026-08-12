<?php

declare(strict_types=1);

namespace App\Model\Booking;

use App\Model\Enums\Booking\BookingStatus;
use App\Model\Shop;
use Carbon\Carbon;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $shop_id
 * @property string $booking_no
 * @property string $status
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property int $service_id
 * @property int $location_id
 * @property int $staff_id
 * @property int $slot_id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property string $lock_token
 * @property null|string $idempotency_key
 * @property string $shopline_order_id
 * @property string $remark
 * @property null|Carbon $cancelled_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property null|BookingService $service
 * @property null|BookingLocation $location
 * @property null|BookingStaff $staff
 * @property null|BookingSlot $slot
 * @property Collection|BookingItem[] $items
 */
final class Booking extends Model
{
    protected ?string $table = 'bookings';

    protected array $fillable = [
        'shop_id',
        'booking_no',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_id',
        'location_id',
        'staff_id',
        'slot_id',
        'start_at',
        'end_at',
        'lock_token',
        'idempotency_key',
        'shopline_order_id',
        'remark',
        'cancelled_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'service_id' => 'integer',
        'location_id' => 'integer',
        'staff_id' => 'integer',
        'slot_id' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(BookingService::class, 'service_id', 'id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(BookingLocation::class, 'location_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(BookingStaff::class, 'staff_id', 'id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(BookingSlot::class, 'slot_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class, 'booking_id', 'id');
    }

    public function isPending(): bool
    {
        return $this->status === BookingStatus::Pending->value;
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::Confirmed->value;
    }
}
