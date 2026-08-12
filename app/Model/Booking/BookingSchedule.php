<?php

declare(strict_types=1);

namespace App\Model\Booking;

use App\Model\Shop;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $shop_id
 * @property int $staff_id
 * @property null|int $location_id
 * @property int $weekday
 * @property string $start_time
 * @property string $end_time
 * @property int $status
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property null|BookingStaff $staff
 * @property null|BookingLocation $location
 */
final class BookingSchedule extends Model
{
    public const STATUS_ENABLED = 1;

    public const STATUS_DISABLED = 2;

    protected ?string $table = 'booking_schedules';

    protected array $fillable = [
        'shop_id',
        'staff_id',
        'location_id',
        'weekday',
        'start_time',
        'end_time',
        'status',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'staff_id' => 'integer',
        'location_id' => 'integer',
        'weekday' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(BookingStaff::class, 'staff_id', 'id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(BookingLocation::class, 'location_id', 'id');
    }
}
