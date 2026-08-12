<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\BookingSlot;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<BookingSlot>
 */
final class BookingSlotRepository extends IRepository
{
    public function __construct(
        protected readonly BookingSlot $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->where('shop_id', (int) $params['shop_id']);
            })
            ->when(isset($params['service_id']), static function (Builder $q) use ($params) {
                $q->where('service_id', (int) $params['service_id']);
            })
            ->when(isset($params['location_id']), static function (Builder $q) use ($params) {
                $q->where('location_id', (int) $params['location_id']);
            })
            ->when(isset($params['staff_id']), static function (Builder $q) use ($params) {
                $q->where('staff_id', (int) $params['staff_id']);
            })
            ->when(isset($params['status']), static function (Builder $q) use ($params) {
                $q->where('status', (string) $params['status']);
            });
    }
}
