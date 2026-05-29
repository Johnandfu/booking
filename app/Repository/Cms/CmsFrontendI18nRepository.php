<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsFrontendI18n;
use App\Repository\IRepository;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsFrontendI18n>
 */
final class CmsFrontendI18nRepository extends IRepository
{
    public function __construct(
        protected readonly CmsFrontendI18n $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status'])) {
            $query->where('status', (int) $params['status']);
        }

        if (! empty($params['group_name'])) {
            $query->where('group_name', (string) $params['group_name']);
        }

        if (! empty($params['keyword'])) {
            $query->where(static function (Builder $query) use ($params): void {
                $query->where('key', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('title', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('en', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('zh', 'like', '%' . $params['keyword'] . '%');
            });
        }

        return $query->orderBy('sort')->orderBy('id');
    }

    /**
     * @return Collection<int, CmsFrontendI18n>
     */
    public function listEnabled(): Collection
    {
        return $this->model->newQuery()
            ->where('status', CmsFrontendI18n::STATUS_ENABLED)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }
}
