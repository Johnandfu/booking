<?php

declare(strict_types=1);

namespace App\Repository\Cms;

use App\Model\Cms\CmsContactMessage;
use App\Repository\IRepository;
use Hyperf\Database\Model\Builder;

/**
 * @extends IRepository<CmsContactMessage>
 */
final class CmsContactMessageRepository extends IRepository
{
    public function __construct(
        protected readonly CmsContactMessage $model
    ) {}

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status'])) {
            $query->where('status', (int) $params['status']);
        }

        if (! empty($params['keyword'])) {
            $query->where(static function (Builder $query) use ($params): void {
                $query->where('name', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('email', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('phone', 'like', '%' . $params['keyword'] . '%');
            });
        }

        return $query->orderByDesc('id');
    }
}
