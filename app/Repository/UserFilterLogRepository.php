<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Repository;

use App\Model\UserFilterLog;
use Hyperf\Collection\Arr;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<UserFilterLog>
 */
final class UserFilterLogRepository extends IRepository
{
    public function __construct(
        protected readonly UserFilterLog $model
    ) {}


    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query

            ->when(Arr::get($params, 'created_at'), static function (Builder $query, $created_at) {
                $query->whereBetween('created_at', $created_at);
            })
            ->when(Arr::get($params, 'updated_at'), static function (Builder $query, $updated_at) {
                $query->whereBetween('updated_at', $updated_at);
            })
            ->when(Arr::get($params, 'ip'), static function (Builder $query, $ip) {
                $query->whereIn('ip', Arr::wrap($ip));
            });
    }
}
