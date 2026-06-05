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

        $languages = $this->repository->handleSearch($this->repository->getQuery(), $filter)
            ->get();
        foreach ($languages as $language) {
            $messages[$language->group_type . '-' . $language->key] = (string) $language->value;
        }

        return $messages + $languages->toArray();
    }
}
