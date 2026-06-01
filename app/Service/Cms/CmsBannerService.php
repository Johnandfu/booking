<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsBanner;
use App\Repository\Cms\CmsBannerRepository;
use App\Service\IService;
use Hyperf\Collection\Collection;
use Throwable;

/**
 * @extends IService<CmsBanner>
 */
final class CmsBannerService extends IService
{
    public function __construct(
        protected readonly CmsBannerRepository $repository
    ) {}


    /**
     * @return Collection<int, CmsBanner>
     */
    public function banners(): Collection
    {
        try {
            return $this->repository->listEnabled();
        } catch (Throwable) {
            return new Collection();
        }
    }
}
