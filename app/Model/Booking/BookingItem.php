<?php

declare(strict_types=1);

namespace App\Model\Booking;

use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $booking_id
 * @property int $service_id
 * @property string $title
 * @property int $quantity
 * @property null|array $meta
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Booking $booking
 * @property null|BookingService $service
 */
final class BookingItem extends Model
{
    protected ?string $table = 'booking_items';

    protected array $fillable = [
        'booking_id',
        'service_id',
        'title',
        'quantity',
        'meta',
    ];

    protected array $casts = [
        'id' => 'integer',
        'booking_id' => 'integer',
        'service_id' => 'integer',
        'quantity' => 'integer',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(BookingService::class, 'service_id', 'id');
    }
}
