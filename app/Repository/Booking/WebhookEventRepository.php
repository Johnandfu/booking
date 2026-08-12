<?php

declare(strict_types=1);

namespace App\Repository\Booking;

use App\Model\Booking\WebhookEvent;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<WebhookEvent>
 */
final class WebhookEventRepository extends IRepository
{
    public function __construct(
        protected readonly WebhookEvent $model
    ) {}

    public function findByEventId(string $eventId): ?WebhookEvent
    {
        /** @var null|WebhookEvent $row */
        $row = $this->getQuery()->where('event_id', $eventId)->first();

        return $row;
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            ->when(isset($params['shop_id']), static function (Builder $q) use ($params) {
                $q->where('shop_id', (int) $params['shop_id']);
            })
            ->when(isset($params['topic']), static function (Builder $q) use ($params) {
                $q->where('topic', (string) $params['topic']);
            })
            ->when(isset($params['status']), static function (Builder $q) use ($params) {
                $q->where('status', (string) $params['status']);
            })
            ->when(isset($params['event_id']), static function (Builder $q) use ($params) {
                $q->where('event_id', (string) $params['event_id']);
            });
    }
}
