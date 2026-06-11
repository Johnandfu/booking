<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsTestimonial;
use App\Repository\IRepository;
use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsTestimonial>
 */
final class CmsTestimonialRepository extends IRepository
{
    public function __construct(
        protected readonly CmsTestimonial $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(Arr::has($params, 'status'), static function (Builder $query) use ($params): void {
                $query->where('status', (int) $params['status']);
            })
            ->when(Arr::get($params, 'keyword'), static function (Builder $query, string $keyword): void {
                $query->where(static function (Builder $query) use ($keyword): void {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('position', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                });
            })
            ->when(Arr::get($params, 'created_at'), static function (Builder $query, array $createdAt): void {
                $query->whereBetween('created_at', $createdAt);
            })
            ->when(Arr::get($params, 'updated_at'), static function (Builder $query, array $updatedAt): void {
                $query->whereBetween('updated_at', $updatedAt);
            })
            ->orderByDesc('sort')
            ->orderByDesc('id');
    }

    /**
     * @return Collection<int, CmsTestimonial>
     */
    public function listEnabled(): Collection
    {
        return $this->list(['status' => CmsTestimonial::STATUS_ENABLED]);
    }
}
