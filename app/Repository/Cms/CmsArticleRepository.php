<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsArticle;
use App\Repository\IRepository;
use Hyperf\Collection\Collection;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsArticle>
 */
final class CmsArticleRepository extends IRepository
{
    public function __construct(
        protected readonly CmsArticle $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        $query->with('category');

        if (isset($params['category_id'])) {
            $query->where('category_id', (int) $params['category_id']);
        }

        if (isset($params['status'])) {
            $query->where('status', (int) $params['status']);
        }

        if (! empty($params['keyword'])) {
            $query->where(static function (Builder $query) use ($params): void {
                $query->where('title', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('slug', 'like', '%' . $params['keyword'] . '%');
            });
        }

        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function findPublishedBySlugAndCategory(string $slug, int $categoryId): ?CmsArticle
    {
        return $this->model->newQuery()
            ->with('category')
            ->where('slug', $slug)
            ->where('category_id', $categoryId)
            ->where('status', CmsArticle::STATUS_PUBLISHED)
            ->first();
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function latestPublished(int $limit = 6): Collection
    {
        return $this->model->newQuery()
            ->with('category')
            ->where('status', CmsArticle::STATUS_PUBLISHED)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function paginatePublishedByCategory(int $categoryId, int $page, int $pageSize): LengthAwarePaginatorInterface
    {
        return $this->model->newQuery()
            ->where('category_id', $categoryId)
            ->where('status', CmsArticle::STATUS_PUBLISHED)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(perPage: $pageSize, page: $page);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function latestPublishedByCategory(int $categoryId, int $limit): Collection
    {
        return $this->model->newQuery()
            ->with('category')
            ->where('category_id', $categoryId)
            ->where('status', CmsArticle::STATUS_PUBLISHED)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function incrementViews(int $id): void
    {
        $this->model->newQuery()->whereKey($id)->increment('views');
    }
}
