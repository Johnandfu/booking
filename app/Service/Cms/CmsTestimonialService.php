<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsTestimonial;
use App\Repository\Cms\CmsTestimonialRepository;
use App\Service\IService;
use Hyperf\Collection\Collection;
use Throwable;

/**
 * @extends IService<CmsTestimonial>
 */
final class CmsTestimonialService extends IService
{
    public function __construct(
        protected readonly CmsTestimonialRepository $repository
    ) {}

    /**
     * @return Collection<int, CmsTestimonial>
     */
    public function enabledTestimonials(): Collection
    {
        try {
            return $this->repository->listEnabled();
        } catch (Throwable) {
            return new Collection();
        }
    }
}
