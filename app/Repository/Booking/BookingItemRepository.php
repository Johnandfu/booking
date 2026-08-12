<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\BookingItem;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<BookingItem>
 */
final class BookingItemRepository extends IRepository
{
    public function __construct(
        protected readonly BookingItem $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->whereHas('booking', static function (Builder $bq) use ($params) {
                    $bq->where('shop_id', (int) $params['shop_id']);
                });
            })
            ->when(isset($params['booking_id']), static function (Builder $q) use ($params) {
                $q->where('booking_id', (int) $params['booking_id']);
            })
            ->when(isset($params['service_id']), static function (Builder $q) use ($params) {
                $q->where('service_id', (int) $params['service_id']);
            });
    }
}
