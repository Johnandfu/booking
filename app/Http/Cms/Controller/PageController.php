<?php

declare(strict_types=1);

namespace App\Http\Cms\Controller;

use App\Http\Cms\Support\CmsView;
use App\Service\Cms\CmsContentService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponseInterface;
use Psr\Http\Message\ResponseInterface;

final class PageController
{
    #[Inject]
    private CmsContentService $cmsContent;

    #[Inject]
    private CmsView $view;

    public function show(string $slug, HttpResponseInterface $response): ResponseInterface
    {
        $page = $this->cmsContent->pageBySlug($slug);
        if ($page === null) {
            $content = $this->view->render('pages/not-found');
            return $response->html($this->view->layout('页面不存在', $content, $this->cmsContent->navCategories()));
        }

        $categories = $this->cmsContent->navCategories();
        $content = $this->view->render('pages/page', compact('page'));

        return $response->html($this->view->layout(
            $page->seo_title ?: $page->title,
            $content,
            $categories,
            $page->seo_description,
        ));
    }
}
