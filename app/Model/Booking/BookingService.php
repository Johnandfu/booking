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
 * @property string $description
 * @property int $duration_minutes
 * @property int $capacity
 * @property int $buffer_minutes
 * @property int $status
 * @property int $sort
 * @property string $shopline_product_id
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property Collection|BookingStaff[] $staff
 * @property Collection|BookingSlot[] $slots
 * @property Collection|Booking[] $bookings
 */
final class BookingService extends Model
{
    public const STATUS_ENABLED = 1;

    public const STATUS_DISABLED = 2;

    protected ?string $table = 'booking_services';

    protected array $fillable = [
        'shop_id',
        'name',
        'description',
        'duration_minutes',
        'capacity',
        'buffer_minutes',
        'status',
        'sort',
        'shopline_product_id',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'duration_minutes' => 'integer',
        'capacity' => 'integer',
        'buffer_minutes' => 'integer',
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
            'booking_staff_service',
            'service_id',
            'staff_id'
        );
    }

    public function slots(): HasMany
    {
        return $this->hasMany(BookingSlot::class, 'service_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'service_id', 'id');
    }
}
