<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\BookingLocation;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<BookingLocation>
 */
final class BookingLocationRepository extends IRepository
{
    public function __construct(
        protected readonly BookingLocation $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->where('shop_id', (int) $params['shop_id']);
            })
            ->when(isset($params['status']), static function (Builder $q) use ($params) {
                $q->where('status', (int) $params['status']);
            })
            ->when(isset($params['name']), static function (Builder $q) use ($params) {
                $q->where('name', 'like', '%' . (string) $params['name'] . '%');
            });
    }
}
