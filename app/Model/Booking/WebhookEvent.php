<?php

declare(strict_types=1);

namespace App\Model\Booking;

use App\Model\Shop;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property null|int $shop_id
 * @property string $topic
 * @property string $event_id
 * @property array $payload
 * @property string $status
 * @property null|Carbon $processed_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 */
final class WebhookEvent extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_FAILED = 'failed';

    protected ?string $table = 'webhook_events';

    protected array $fillable = [
        'shop_id',
        'topic',
        'event_id',
        'payload',
        'status',
        'processed_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'payload' => 'array',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
}
