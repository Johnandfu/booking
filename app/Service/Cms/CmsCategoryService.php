<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsCategory;
use App\Repository\Cms\CmsCategoryRepository;
use App\Service\IService;

/**
 * @extends IService<CmsCategory>
 */
final class CmsCategoryService extends IService
{
    public function __construct(
        protected readonly CmsCategoryRepository $repository
    ) {}

    public function categoryBySlug(string $slug): ?CmsCategory
    {
        return $this->repository->findBySlug($slug);
    }

    public function categoryPath(string $slug): string
    {
        return match ($slug) {
            'news' => '/news',
            'service' => '/service',
            default => '/category/' . $slug,
        };
    }
}
