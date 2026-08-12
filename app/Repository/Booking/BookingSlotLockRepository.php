<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\BookingSlotLock;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<BookingSlotLock>
 */
final class BookingSlotLockRepository extends IRepository
{
    public function __construct(
        protected readonly BookingSlotLock $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->where('shop_id', (int) $params['shop_id']);
            })
            ->when(isset($params['slot_id']), static function (Builder $q) use ($params) {
                $q->where('slot_id', (int) $params['slot_id']);
            })
            ->when(isset($params['lock_token']), static function (Builder $q) use ($params) {
                $q->where('lock_token', (string) $params['lock_token']);
            })
            ->when(isset($params['status']), static function (Builder $q) use ($params) {
                $q->where('status', (string) $params['status']);
            });
    }
}
