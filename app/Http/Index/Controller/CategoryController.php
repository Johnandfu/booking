<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsArticleService;
use App\Service\Cms\CmsCategoryService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class CategoryController extends BaseController
{
    public function __construct(
        CmsLanguageService $cmsLangcode,
        CmsCategoryService $cmsCategory,
        RequestInterface $request,
        protected readonly CmsArticleService $cmsArticle,
    ) {
        parent::__construct($cmsLangcode, $cmsCategory, $request);
    }

    public function index(string $categorySlug, RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug($categorySlug);
        if ($category === null) {
            return $this->notFound($render);
        }

        $page = max(1, (int) $request->input('page', 1));
        $name = trim((string) $request->input('name', ''));
        $basePath = '/' . $category->slug;

        if ($category->slug === 'news') {
            $data = $this->cmsArticle->newsList($category->id, $page, $name);

            return $this->view($render, 'index.news', array_merge($data, [
                'title' => $category->name,
                'description' => $category->description,
                'activeNav' => $category->slug,
                'category' => $category,
                'basePath' => $basePath,
                'name' => $name,
            ]));
        }

        $data = $this->cmsArticle->serviceList($category->id, $page, $name);

        return $this->view($render, 'index.category', array_merge($data, [
            'title' => $category->name,
            'description' => $category->description,
            'activeNav' => $category->slug,
            'category' => $category,
            'basePath' => $basePath,
            'name' => $name,
        ]));
    }

    public function show(string $categorySlug, string $slug, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug($categorySlug);
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
            'activeNav' => $category->slug,
            'article' => $article,
            'category' => $category,
        ]);
    }
}
