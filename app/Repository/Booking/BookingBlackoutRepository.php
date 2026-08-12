<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\BookingBlackout;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<BookingBlackout>
 */
final class BookingBlackoutRepository extends IRepository
{
    public function __construct(
        protected readonly BookingBlackout $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->where('shop_id', (int) $params['shop_id']);
            })
            ->when(isset($params['staff_id']), static function (Builder $q) use ($params) {
                $q->where('staff_id', (int) $params['staff_id']);
            })
            ->when(isset($params['location_id']), static function (Builder $q) use ($params) {
                $q->where('location_id', (int) $params['location_id']);
            });
    }
}
