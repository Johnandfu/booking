<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\BookingHoliday;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<BookingHoliday>
 */
final class BookingHolidayRepository extends IRepository
{
    public function __construct(
        protected readonly BookingHoliday $model
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
            ->when(isset($params['holiday_date']), static function (Builder $q) use ($params) {
                $q->where('holiday_date', (string) $params['holiday_date']);
            });
    }
}
