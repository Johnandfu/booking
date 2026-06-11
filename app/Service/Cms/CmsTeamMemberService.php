<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsTeamMember;
use App\Repository\Cms\CmsTeamMemberRepository;
use App\Service\IService;
use Hyperf\Collection\Collection;
use Throwable;

/**
 * @extends IService<CmsTeamMember>
 */
final class CmsTeamMemberService extends IService
{
    public function __construct(
        protected readonly CmsTeamMemberRepository $repository
    ) {}

    /**
     * @return Collection<int, CmsTeamMember>
     */
    public function enabledMembers(): Collection
    {
        try {
            return $this->repository->listEnabled();
        } catch (Throwable) {
            return new Collection();
        }
    }
}
