<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsArticle;
use App\Repository\IRepository;
use Hyperf\Collection\Arr;
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
        return $query->with('category')
            ->when(Arr::has($params, 'category_id'), static function (Builder $query) use ($params): void {
                $query->where('category_id', (int) $params['category_id']);
            })
            ->when(Arr::has($params, 'status'), static function (Builder $query) use ($params): void {
                $query->where('status', (int) $params['status']);
            })
            ->when(Arr::get($params, 'keyword'), static function (Builder $query, string $keyword): void {
                $query->where(static function (Builder $query) use ($keyword): void {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('slug', 'like', "%{$keyword}%");
                });
            })
            ->when(Arr::get($params, 'created_at'), static function (Builder $query, array $createdAt): void {
                $query->whereBetween('created_at', $createdAt);
            })
            ->when(Arr::get($params, 'updated_at'), static function (Builder $query, array $updatedAt): void {
                $query->whereBetween('updated_at', $updatedAt);
            });
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
            ->orderByDesc('sort')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function paginatePublishedByCategory(int $categoryId, int $page, int $pageSize, string $name = ''): LengthAwarePaginatorInterface
    {
        $query = $this->model->newQuery()
            ->where('category_id', $categoryId)
            ->where('status', CmsArticle::STATUS_PUBLISHED);

        if ($name !== '') {
            $query->where('title', 'like', '%' . $name . '%');
        }

        return $query
            ->orderByDesc('sort')
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
            ->orderByDesc('sort')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function publishedByCategory(int $categoryId): Collection
    {
        return $this->model->newQuery()
            ->with('category')
            ->where('category_id', $categoryId)
            ->where('status', CmsArticle::STATUS_PUBLISHED)
            ->orderByDesc('sort')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();
    }

    public function incrementViews(int $id): void
    {
        $this->model->newQuery()->whereKey($id)->increment('views');
    }

    public function existsBySlug(string $slug, ?int $exceptId = null): bool
    {
        $query = $this->model->newQuery()->where('slug', $slug);
        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
}
