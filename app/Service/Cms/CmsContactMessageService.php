<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsContactMessage;
use App\Repository\Cms\CmsContactMessageRepository;
use App\Service\IService;

/**
 * @extends IService<CmsContactMessage>
 */
final class CmsContactMessageService extends IService
{
    public function __construct(
        protected readonly CmsContactMessageRepository $repository
    ) {}


    public function createContactMessage(array $data): CmsContactMessage
    {
        return $this->repository->create($data);
    }
}
