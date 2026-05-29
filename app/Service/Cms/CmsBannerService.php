<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsBanner;
use App\Repository\Cms\CmsBannerRepository;
use App\Service\IService;

/**
 * @extends IService<CmsBanner>
 */
final class CmsBannerService extends IService
{
    public function __construct(
        protected readonly CmsBannerRepository $repository
    ) {}
}
