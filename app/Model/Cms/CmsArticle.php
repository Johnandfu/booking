<?php

declare(strict_types=1);

namespace App\Model\Cms;

use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $summary
 * @property string|null $content
 * @property string|null $cover
 * @property string|null $author
 * @property int $views
 * @property int $sort
 * @property int $status
 * @property Carbon|null $published_at
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class CmsArticle extends MineModel
{
    public const STATUS_DRAFT = 0;

    public const STATUS_PUBLISHED = 1;

    protected ?string $table = 'cms_article';

    protected array $fillable = [
        'id', 'category_id', 'title', 'slug', 'summary', 'content', 'cover', 'author',
        'views', 'sort', 'status', 'published_at', 'seo_title', 'seo_description', 'created_at', 'updated_at',
    ];

    protected array $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'views' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CmsCategory::class, 'category_id', 'id');
    }
}
