<?php

declare(strict_types=1);

namespace App\Model;

use Carbon\Carbon;
use Hyperf\Database\Model\Relations\HasOne;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $handle
 * @property string $domain
 * @property string $timezone
 * @property string $locale
 * @property int $status
 * @property null|Carbon $installed_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|ShopToken $token
 */
final class Shop extends Model
{
    public const STATUS_INSTALLED = 1;

    public const STATUS_DISABLED = 2;

    protected ?string $table = 'shops';

    protected array $fillable = [
        'handle',
        'domain',
        'timezone',
        'locale',
        'status',
        'installed_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'installed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function token(): HasOne
    {
        return $this->hasOne(ShopToken::class, 'shop_id', 'id');
    }
}
