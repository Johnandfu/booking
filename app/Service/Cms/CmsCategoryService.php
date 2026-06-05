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

    /** @var array<string, string> 前台 URL 别名 → 数据库 slug */
    private const SLUG_ALIASES = [
        'services' => 'service',
    ];

    public function categoryBySlug(string $slug): ?CmsCategory
    {
        $category = $this->repository->findBySlug($slug);
        if ($category !== null) {
            return $category;
        }

        $canonical = self::SLUG_ALIASES[$slug] ?? null;
        if ($canonical === null) {
            return null;
        }

        return $this->repository->findBySlug($canonical);
    }

    /**
     * @return Collection<int, CmsCategory>
     */
    public function enabledCategories(): Collection
    {
        return $this->repository->listEnabled();
    }
}
