<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Shop;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<Shop>
 */
final class ShopRepository extends IRepository
{
    public function __construct(
        protected readonly Shop $model
    ) {}

    public function findByHandle(string $handle): ?Shop
    {
        /** @var null|Shop $shop */
        $shop = $this->getQuery()->where('handle', $handle)->first();

        return $shop;
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['handle']), static function (Builder $q) use ($params) {
                $q->where('handle', (string) $params['handle']);
            })
            ->when(isset($params['status']), static function (Builder $q) use ($params) {
                $q->where('status', (int) $params['status']);
            });
    }
}
