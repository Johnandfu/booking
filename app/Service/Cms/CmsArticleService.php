<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsArticle;
use App\Repository\Cms\CmsArticleRepository;
use App\Service\IService;

/**
 * @extends IService<CmsArticle>
 */
final class CmsArticleService extends IService
{
    public function __construct(
        protected readonly CmsArticleRepository $repository
    ) {}
}
