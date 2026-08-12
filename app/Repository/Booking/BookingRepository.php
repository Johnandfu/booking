<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\Booking;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<Booking>
 */
final class BookingRepository extends IRepository
{
    public function __construct(
        protected readonly Booking $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->where('shop_id', (int) $params['shop_id']);
            })
            ->when(isset($params['status']), static function (Builder $q) use ($params) {
                $q->where('status', (string) $params['status']);
            })
            ->when(isset($params['booking_no']), static function (Builder $q) use ($params) {
                $q->where('booking_no', (string) $params['booking_no']);
            })
            ->when(isset($params['slot_id']), static function (Builder $q) use ($params) {
                $q->where('slot_id', (int) $params['slot_id']);
            })
            ->when(isset($params['idempotency_key']), static function (Builder $q) use ($params) {
                $q->where('idempotency_key', (string) $params['idempotency_key']);
            })
            ->when(isset($params['service_id']), static function (Builder $q) use ($params) {
                $q->where('service_id', (int) $params['service_id']);
            })
            ->when(! empty($params['start_from']), static function (Builder $q) use ($params) {
                $q->where('start_at', '>=', (string) $params['start_from']);
            })
            ->when(! empty($params['start_to']), static function (Builder $q) use ($params) {
                $q->where('start_at', '<=', (string) $params['start_to']);
            });
    }
}
