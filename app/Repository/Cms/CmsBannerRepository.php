<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsBanner;
use App\Repository\IRepository;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsBanner>
 */
final class CmsBannerRepository extends IRepository
{
    public function __construct(
        protected readonly CmsBanner $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status'])) {
            $query->where('status', (int) $params['status']);
        }

        if (! empty($params['keyword'])) {
            $query->where('title', 'like', '%' . $params['keyword'] . '%');
        }

        return $query->orderByDesc('sort')->orderByDesc('id');
    }

    /**
     * @return Collection<int, CmsBanner>
     */
    public function listEnabled(): Collection
    {
        return $this->list(['status' => CmsBanner::STATUS_ENABLED]);
    }
}
