<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsPage;
use App\Repository\Cms\CmsPageRepository;
use App\Service\IService;

/**
 * @extends IService<CmsPage>
 */
final class CmsPageService extends IService
{
    public function __construct(
        protected readonly CmsPageRepository $repository
    ) {}
}
