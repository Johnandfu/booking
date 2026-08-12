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
 * @property string $address
 * @property string $phone
 * @property int $status
 * @property int $sort
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property Collection|BookingStaff[] $staff
 * @property Collection|BookingSlot[] $slots
 * @property Collection|Booking[] $bookings
 */
final class BookingLocation extends Model
{
    public const STATUS_ENABLED = 1;

    public const STATUS_DISABLED = 2;

    protected ?string $table = 'booking_locations';

    protected array $fillable = [
        'shop_id',
        'name',
        'address',
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

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(
            BookingStaff::class,
            'booking_staff_location',
            'location_id',
            'staff_id'
        );
    }

    public function slots(): HasMany
    {
        return $this->hasMany(BookingSlot::class, 'location_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'location_id', 'id');
    }
}
