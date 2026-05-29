<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsCategory;
use App\Repository\IRepository;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsCategory>
 */
final class CmsCategoryRepository extends IRepository
{
    public function __construct(
        protected readonly CmsCategory $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status'])) {
            $query->where('status', (int) $params['status']);
        }

        if (! empty($params['keyword'])) {
            $query->where(static function (Builder $query) use ($params): void {
                $query->where('name', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('slug', 'like', '%' . $params['keyword'] . '%');
            });
        }

        return $query->orderByDesc('sort')->orderBy('id');
    }

    /**
     * @return Collection<int, CmsCategory>
     */
    public function listEnabled(): Collection
    {
        return $this->list(['status' => CmsCategory::STATUS_ENABLED]);
    }

    public function findBySlug(string $slug): ?CmsCategory
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('status', CmsCategory::STATUS_ENABLED)
            ->first();
    }
}
