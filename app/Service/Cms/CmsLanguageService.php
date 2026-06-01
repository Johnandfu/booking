<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsLanguage;
use App\Repository\Cms\CmsLanguageRepository;
use App\Service\IService;

/**
 * @extends IService<CmsLanguage>
 */
final class CmsLanguageService extends IService
{
    public function __construct(
        protected readonly CmsLanguageRepository $repository
    ) {}


    public function copy(array $filter = []): array
    {
        $messages = [];
        foreach ($this->repository->handleSearch($this->repository->getQuery(), $filter)->get() as $item) {
            $messages[$item->group_type . '-' . $item->key] = (string) $item->value;
        }

        return $messages;
    }
}
