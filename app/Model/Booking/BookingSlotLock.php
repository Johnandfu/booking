<?php

declare(strict_types=1);

namespace App\Model\Booking;

use App\Model\Enums\Booking\SlotLockStatus;
use App\Model\Shop;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $shop_id
 * @property int $slot_id
 * @property string $lock_token
 * @property string $customer_key
 * @property Carbon $expire_at
 * @property string $status
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 * @property null|BookingSlot $slot
 */
final class BookingSlotLock extends Model
{
    protected ?string $table = 'booking_slot_locks';

    protected array $fillable = [
        'shop_id',
        'slot_id',
        'lock_token',
        'customer_key',
        'expire_at',
        'status',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'slot_id' => 'integer',
        'expire_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(BookingSlot::class, 'slot_id', 'id');
    }

    public function isActive(): bool
    {
        return $this->status === SlotLockStatus::Active->value;
    }
}
