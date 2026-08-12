<?php

declare(strict_types=1);

namespace App\Model\Booking;

use App\Model\Enums\Booking\SlotStatus;
use App\Model\Shop;
use Carbon\Carbon;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $shop_id
 * @property int $service_id
 * @property int $location_id
 * @property int $staff_id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property int $capacity
 * @property int $booked
 * @property string $status
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property null|BookingService $service
 * @property null|BookingLocation $location
 * @property null|BookingStaff $staff
 * @property Collection|BookingSlotLock[] $locks
 * @property Collection|Booking[] $bookings
 */
final class BookingSlot extends Model
{
    protected ?string $table = 'booking_slots';

    protected array $fillable = [
        'shop_id',
        'service_id',
        'location_id',
        'staff_id',
        'start_at',
        'end_at',
        'capacity',
        'booked',
        'status',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'service_id' => 'integer',
        'location_id' => 'integer',
        'staff_id' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'capacity' => 'integer',
        'booked' => 'integer',
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

    public function locks(): HasMany
    {
        return $this->hasMany(BookingSlotLock::class, 'slot_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'slot_id', 'id');
    }

    public function isAvailable(): bool
    {
        return $this->status === SlotStatus::Available->value;
    }
}
