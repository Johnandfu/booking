<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsPage;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsPage>
 */
final class CmsPageRepository extends IRepository
{
    public function __construct(
        protected readonly CmsPage $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status'])) {
            $query->where('status', (int) $params['status']);
        }

        if (! empty($params['keyword'])) {
            $query->where(static function (Builder $query) use ($params): void {
                $query->where('title', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('slug', 'like', '%' . $params['keyword'] . '%');
            });
        }

        return $query->orderByDesc('id');
    }

    public function findEnabledBySlug(string $slug): ?CmsPage
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->where('status', CmsPage::STATUS_ENABLED)
            ->first();
    }
}
