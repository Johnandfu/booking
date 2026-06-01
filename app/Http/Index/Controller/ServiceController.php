<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsArticleService;
use App\Service\Cms\CmsCategoryService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class ServiceController extends BaseController
{
    #[Inject]
    protected CmsArticleService $cmsArticle;
    #[Inject]
    protected CmsCategoryService $cmsCategory;

    public function index(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug('service');
        if ($category === null) {
            return $this->notFound($render);
        }

        $page = max(1, (int) $request->input('page', 1));
        $data = $this->cmsArticle->serviceList($category->id, $page);

        return $this->view($render, 'index.category', array_merge($data, [
            'title' => $category->name,
            'description' => $category->description,
            'activeNav' => 'services',
            'category' => $category,
            'basePath' => $this->cmsCategory->categoryPath('service'),
        ]));
    }

    public function show(string $slug, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug('service');
        if ($category === null) {
            return $this->notFound($render);
        }

        $article = $this->cmsArticle->articleBySlugAndCategory($slug, $category->id);
        if ($article === null) {
            return $this->notFound($render);
        }

        $this->cmsArticle->incrementViews($article->id);

        return $this->view($render, 'index.article', [
            'title' => $article->seo_title ?: $article->title,
            'description' => $article->seo_description ?: $article->summary,
            'activeNav' => 'services',
            'article' => $article,
        ]);
    }
}
