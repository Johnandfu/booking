<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property int $status
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsPage extends MineModel
{
    public const STATUS_DISABLED = 0;

    public const STATUS_ENABLED = 1;

    protected ?string $table = 'cms_page';

    protected array $fillable = [
        'id', 'title', 'slug', 'content', 'status', 'seo_title', 'seo_description', 'created_at', 'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
