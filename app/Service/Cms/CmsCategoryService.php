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
}
