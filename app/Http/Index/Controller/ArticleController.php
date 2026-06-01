<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class ArticleController extends BaseController
{
    public function news(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsContent->categoryBySlug('news');
        if ($category === null) {
            return $this->notFound($render);
        }

        $page = max(1, (int) $request->input('page', 1));
        $paginator = $this->cmsContent->articlesByCategory($category->id, $page, 10);

        return $this->view($render, 'index.news', [
            'title' => $category->name,
            'description' => $category->description,
            'activeNav' => 'category:' . $category->slug,
            'category' => $category,
            'paginator' => $paginator,
            'basePath' => $this->cmsContent->categoryPath('news'),
        ]);
    }

    public function service(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        return $this->category('service', $request, $render);
    }

    public function category(string $slug, RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsContent->categoryBySlug($slug);
        if ($category === null) {
            return $this->notFound($render);
        }

        $page = max(1, (int) $request->input('page', 1));
        $perPage = $category->slug === 'service' ? 12 : 10;
        $paginator = $this->cmsContent->articlesByCategory($category->id, $page, $perPage);

        return $this->view($render, 'index.category', [
            'title' => $category->name,
            'description' => $category->description,
            'activeNav' => 'category:' . $category->slug,
            'category' => $category,
            'paginator' => $paginator,
            'basePath' => $this->cmsContent->categoryPath($slug),
        ]);
    }

    public function show(string $slug, RenderInterface $render): ResponseInterface
    {
        $article = $this->cmsContent->articleBySlug($slug);
        if ($article === null) {
            return $this->notFound($render);
        }

        $this->cmsContent->recordArticleView($article);

        return $this->view($render, 'index.article', [
            'title' => $article->seo_title ?: $article->title,
            'description' => $article->seo_description ?: $article->summary,
            'activeNav' => null,
            'article' => $article,
        ]);
    }
}
