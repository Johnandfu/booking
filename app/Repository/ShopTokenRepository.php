<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\ShopToken;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<ShopToken>
 */
final class ShopTokenRepository extends IRepository
{
    public function __construct(
        protected readonly ShopToken $model
    ) {}

    public function findByShopId(int $shopId): ?ShopToken
    {
        /** @var null|ShopToken $token */
        $token = $this->getQuery()->where('shop_id', $shopId)->first();

        return $token;
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
            $q->where('shop_id', (int) $params['shop_id']);
        });
    }
}
