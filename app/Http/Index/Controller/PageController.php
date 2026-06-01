<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsPageService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class PageController extends BaseController
{
    #[Inject]
    protected CmsPageService $cmsPage;
    public function show(string $slug, RenderInterface $render): ResponseInterface
    {
        $page = $this->cmsPage->pageBySlug($slug);
        if ($page === null) {
            return $this->notFound($render);
        }

        return $this->view($render, 'index.page', [
            'title' => $page->seo_title ?: $page->title,
            'description' => $page->seo_description,
            'activeNav' => null,
            'page' => $page,
        ]);
    }
}
