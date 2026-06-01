<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsArticleService;
use App\Service\Cms\CmsCategoryService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class NewsController extends BaseController
{
    public function __construct(
        CmsLanguageService $cmsLangcode,
        CmsCategoryService $cmsCategory,
        RequestInterface $request,
        protected readonly CmsArticleService $cmsArticle,
    ) {
        parent::__construct($cmsLangcode, $cmsCategory, $request);
    }

    public function index(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug('news');
        if ($category === null) {
            return $this->notFound($render);
        }

        $page = max(1, (int) $request->input('page', 1));
        $name = trim((string) $request->input('name', ''));
        $data = $this->cmsArticle->newsList($category->id, $page, $name);

        return $this->view($render, 'index.news', array_merge($data, [
            'title' => $category->name,
            'description' => $category->description,
            'activeNav' => $category->slug,
            'category' => $category,
            'basePath' => '/' . $category->slug,
            'name' => $name,
        ]));
    }

    public function show(string $slug, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug('news');
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
        ]);
    }
}
