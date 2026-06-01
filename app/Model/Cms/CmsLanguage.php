<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property string $group_type
 * @property string $key
 * @property string $title
 * @property string $language_code
 * @property string|null $value
 * @property int $status
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsLanguage extends MineModel
{
    public const STATUS_DISABLED = 0;

    public const STATUS_ENABLED = 1;

    protected ?string $table = 'cms_language';

    protected array $fillable = [
        'id',
        'group_type',
        'key',
        'title',
        'value',
        'language_code',
        'status',
        'sort',
        'created_at',
        'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
