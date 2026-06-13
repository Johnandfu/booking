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

        if ($category->slug === 'news') {
            $data = $this->cmsArticle->newsList($category->id, $page, $name);

            return $this->view($render, 'index.news', array_merge($data, [
                'pageTitle' => 'category-' . $category->slug . 'Title',
                'pageDescription' => 'category-' . $category->slug . 'Description',

                'activeNav' => $category->slug,
                'category' => $category,
                'basePath' => '/' . $category->slug,
                'name' => $name,
            ]));
        }

        $data = $this->cmsArticle->serviceList($category->id, $page, $name);

        return $this->view($render, 'index.category', array_merge($data, [
            'pageTitle' => 'category-' . $category->slug . 'Title',
            'pageDescription' => 'category-' . $category->slug . 'Description',

            'activeNav' => $category->slug,
            'category' => $category,
            'basePath' =>  '/' . $category->slug,
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
            'pageTitleOri' => $article->title,
            'pageDescriptionOri' => $article->summary,
            'activeNav' => $category->slug,
            'article' => $article,
            'category' => $category,
        ]);
    }
}
