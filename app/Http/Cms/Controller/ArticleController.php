<?php

declare(strict_types=1);

namespace App\Http\Cms\Controller;

use App\Http\Cms\Support\CmsView;
use App\Model\Cms\CmsArticle;
use App\Service\Cms\CmsContentService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponseInterface;
use Psr\Http\Message\ResponseInterface;

final class ArticleController
{
    #[Inject]
    private CmsContentService $cmsContent;

    #[Inject]
    private CmsView $view;

    public function category(string $slug, RequestInterface $request, HttpResponseInterface $response): ResponseInterface
    {
        $category = $this->cmsContent->categoryBySlug($slug);
        if ($category === null) {
            return $response->html($this->notFound());
        }

        $page = max(1, (int) $request->input('page', 1));
        $paginator = $this->cmsContent->articlesByCategory($category->id, $page, 10);
        $categories = $this->cmsContent->navCategories();

        $listHtml = '';
        if ($paginator->total() === 0) {
            $listHtml = '<div class="alert alert-secondary">该栏目暂无文章。</div>';
        } else {
            foreach ($paginator->items() as $article) {
                if ($article instanceof CmsArticle) {
                    $listHtml .= $this->articleListItem($article);
                }
            }
        }

        $pagination = $this->pagination($paginator, '/cms/category/' . $category->slug);
        $name = $this->view->e($category->name);
        $description = $this->view->e($category->description ?? $category->name);

        $content = <<<HTML
<main class="container py-5">
    <div class="mb-4">
        <span class="badge text-bg-primary mb-2">栏目</span>
        <h1 class="fw-bold mb-2">{$name}</h1>
        <p class="text-secondary mb-0">{$description}</p>
    </div>
    <div class="vstack gap-3 mb-4">
        {$listHtml}
    </div>
    {$pagination}
</main>
HTML;

        return $response->html($this->view->layout(
            $category->name,
            $content,
            $categories,
            $category->description,
            'category:' . $category->slug,
        ));
    }

    public function show(string $slug, HttpResponseInterface $response): ResponseInterface
    {
        $article = $this->cmsContent->articleBySlug($slug);
        if ($article === null) {
            return $response->html($this->notFound());
        }

        $this->cmsContent->recordArticleView($article);
        $categories = $this->cmsContent->navCategories();

        $title = $this->view->e($article->title);
        $author = $this->view->e($article->author ?? '管理员');
        $date = $this->view->e($this->view->formatDate($article->published_at));
        $categoryName = $this->view->e($article->category?->name ?? '未分类');
        $categoryHref = '/cms/category/' . $this->view->e($article->category?->slug ?? '');
        $body = $article->content ?: '<p class="text-secondary">暂无正文</p>';
        $views = (int) $article->views + 1;

        $content = <<<HTML
<main class="container py-5">
    <article class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
                <a href="{$categoryHref}" class="badge text-bg-primary text-decoration-none mb-3">{$categoryName}</a>
                <h1 class="fw-bold mb-3">{$title}</h1>
                <div class="d-flex flex-wrap gap-3 text-secondary small mb-4 pb-3 border-bottom">
                    <span>作者：{$author}</span>
                    <span>发布：{$date}</span>
                    <span>阅读：{$views}</span>
                </div>
                <div class="article-content">{$body}</div>
            </div>
        </div>
    </article>
</main>
HTML;

        return $response->html($this->view->layout(
            $article->seo_title ?: $article->title,
            $content,
            $categories,
            $article->seo_description ?: $article->summary,
        ));
    }

    private function articleListItem(CmsArticle $article): string
    {
        $title = $this->view->e($article->title);
        $summary = $this->view->e($article->summary ?: '');
        $href = '/cms/article/' . $this->view->e($article->slug);
        $date = $this->view->e($this->view->formatDate($article->published_at));

        return <<<HTML
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-2"><a href="{$href}" class="text-decoration-none">{$title}</a></h2>
        <p class="text-secondary mb-2">{$summary}</p>
        <small class="text-secondary">{$date}</small>
    </div>
</div>
HTML;
    }

    private function pagination(\Hyperf\Contract\LengthAwarePaginatorInterface $paginator, string $basePath): string
    {
        if ($paginator->lastPage() <= 1) {
            return '';
        }

        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $items = '';

        for ($i = 1; $i <= $last; ++$i) {
            $active = $i === $current ? ' active' : '';
            $href = $basePath . ($i > 1 ? '?page=' . $i : '');
            $items .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $href . '">' . $i . '</a></li>';
        }

        return '<nav aria-label="分页"><ul class="pagination justify-content-center">' . $items . '</ul></nav>';
    }

    private function notFound(): string
    {
        $content = <<<'HTML'
<main class="container py-5">
    <div class="text-center py-5">
        <h1 class="display-4 fw-bold">404</h1>
        <p class="lead text-secondary mb-4">页面不存在或内容已下线</p>
        <a href="/cms" class="btn btn-primary">返回首页</a>
    </div>
</main>
HTML;

        return $this->view->layout('页面不存在', $content, $this->cmsContent->navCategories());
    }
}
