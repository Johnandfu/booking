<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property string $title
 * @property string $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int $zoom
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsMapSetting extends MineModel
{
    protected ?string $table = 'cms_map_setting';

    protected array $fillable = [
        'id', 'title', 'address', 'latitude', 'longitude', 'zoom', 'created_at', 'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'zoom' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
