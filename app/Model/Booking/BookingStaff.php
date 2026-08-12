<?php

declare(strict_types=1);

namespace App\Model\Booking;

use App\Model\Shop;
use Carbon\Carbon;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $shop_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $status
 * @property int $sort
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property Collection|BookingService[] $services
 * @property Collection|BookingLocation[] $locations
 * @property Collection|BookingSchedule[] $schedules
 * @property Collection|BookingSlot[] $slots
 * @property Collection|Booking[] $bookings
 */
final class BookingStaff extends Model
{
    public const STATUS_ENABLED = 1;

    public const STATUS_DISABLED = 2;

    protected ?string $table = 'booking_staff';

    protected array $fillable = [
        'shop_id',
        'name',
        'email',
        'phone',
        'status',
        'sort',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            BookingService::class,
            'booking_staff_service',
            'staff_id',
            'service_id'
        );
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(
            BookingLocation::class,
            'booking_staff_location',
            'staff_id',
            'location_id'
        );
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(BookingSchedule::class, 'staff_id', 'id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(BookingSlot::class, 'staff_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'staff_id', 'id');
    }
}
