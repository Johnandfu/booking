<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsCategory;
use App\Repository\Cms\CmsCategoryRepository;
use App\Service\IService;
use Hyperf\Collection\Collection;

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

    /**
     * @return Collection<int, CmsCategory>
     */
    public function enabledCategories(): Collection
    {
        return $this->repository->listEnabled();
    }
}
