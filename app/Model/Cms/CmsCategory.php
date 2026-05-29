<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $sort
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsCategory extends MineModel
{
    public const STATUS_DISABLED = 0;

    public const STATUS_ENABLED = 1;

    protected ?string $table = 'cms_category';

    protected array $fillable = [
        'id', 'name', 'slug', 'description', 'sort', 'status', 'created_at', 'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(CmsArticle::class, 'category_id', 'id');
    }
}
