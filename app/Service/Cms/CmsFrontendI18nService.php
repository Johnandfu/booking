<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsFrontendI18n;
use App\Repository\Cms\CmsFrontendI18nRepository;
use App\Service\IService;

/**
 * @extends IService<CmsFrontendI18n>
 */
final class CmsFrontendI18nService extends IService
{
    public function __construct(
        protected readonly CmsFrontendI18nRepository $repository
    ) {}
}
