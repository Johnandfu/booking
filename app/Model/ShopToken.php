<?php

declare(strict_types=1);

namespace App\Model;

use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $shop_id
 * @property string $access_token
 * @property string $scope
 * @property null|Carbon $expire_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|Shop $shop
 */
final class ShopToken extends Model
{
    protected ?string $table = 'shop_tokens';

    protected array $fillable = [
        'shop_id',
        'access_token',
        'scope',
        'expire_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'expire_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $hidden = [
        'access_token',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
}
