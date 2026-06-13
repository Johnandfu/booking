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
        $locale = (string) ($filter['language_code'] ?? CmsContentService::DEFAULT_FRONTEND_LOCALE);
        $fallbackLocale = CmsContentService::DEFAULT_FRONTEND_LOCALE;
        $messages = [];

        foreach ($this->repository->handleSearch($this->repository->getQuery(), array_merge($filter, [
            'language_code' => $fallbackLocale,
        ]))->get() as $language) {
            $messages[$language->group_type . '-' . $language->key] = (string) $language->value;
        }

        if ($locale === $fallbackLocale) {
            return $messages;
        }

        foreach ($this->repository->handleSearch($this->repository->getQuery(), array_merge($filter, [
            'language_code' => $locale,
        ]))->get() as $language) {
            $value = (string) $language->value;
            if ($value !== '') {
                $messages[$language->group_type . '-' . $language->key] = $value;
            }
        }

        return $messages;
    }
}
