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
 * @property string $holiday_date
 * @property string $name
 * @property int $status
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 */
final class BookingHoliday extends Model
{
    public const STATUS_ENABLED = 1;

    public const STATUS_DISABLED = 2;

    protected ?string $table = 'booking_holidays';

    protected array $fillable = [
        'shop_id',
        'holiday_date',
        'name',
        'status',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'holiday_date' => 'date',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
}
