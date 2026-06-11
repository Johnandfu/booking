<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsLanguage;
use App\Repository\IRepository;
use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsLanguage>
 */
final class CmsLanguageRepository extends IRepository
{
    public function __construct(
        protected readonly CmsLanguage $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(Arr::has($params, 'status'), static function (Builder $query) use ($params): void {
                $query->where('status', (int) $params['status']);
            })
            ->when(Arr::has($params, 'language_code'), static function (Builder $query) use ($params): void {
                $query->where('language_code', (string) $params['language_code']);
            })
            ->when(Arr::get($params, 'group_type'), static function (Builder $query, string $groupType): void {
                $query->where('group_type', $groupType);
            })
            ->when(Arr::get($params, 'key'), static function (Builder $query, string $key): void {
                $query->where('key', $key);
            })
            ->when(Arr::get($params, 'keyword'), static function (Builder $query, string $keyword): void {
                $query->where(static function (Builder $query) use ($keyword): void {
                    $query->where('key', 'like', "%{$keyword}%")
                        ->orWhere('group_type', 'like', "%{$keyword}%")
                        ->orWhere('title', 'like', "%{$keyword}%")
                        ->orWhere('language_code', 'like', "%{$keyword}%")
                        ->orWhere('value', 'like', "%{$keyword}%");
                });
            })
            ->when(Arr::get($params, 'created_at'), static function (Builder $query, array $createdAt): void {
                $query->whereBetween('created_at', $createdAt);
            })
            ->when(Arr::get($params, 'updated_at'), static function (Builder $query, array $updatedAt): void {
                $query->whereBetween('updated_at', $updatedAt);
            });
    }

    /**
     * @return Collection<int, CmsLanguage>
     */
    public function listEnabled(): Collection
    {
        return $this->model->newQuery()
            ->where('status', CmsLanguage::STATUS_ENABLED)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }
}
