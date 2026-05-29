<?php

declare(strict_types=1);

namespace App\Http\Cms\Controller;

use App\Http\Cms\Support\CmsView;
use App\Model\Cms\CmsArticle;
use App\Service\Cms\CmsContentService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponseInterface;
use Psr\Http\Message\ResponseInterface;

final class HomeController
{
    #[Inject]
    private CmsContentService $cmsContent;

    #[Inject]
    private CmsView $view;

    public function index(HttpResponseInterface $response): ResponseInterface
    {
        $categories = $this->cmsContent->navCategories();
        $articles = $this->cmsContent->latestArticles(6);

        $articleCards = '';
        if ($articles->isEmpty()) {
            $articleCards = '<div class="col-12"><div class="alert alert-info mb-0">暂无文章，请执行迁移并导入示例数据：<code>php bin/hyperf.php migrate</code></div></div>';
        } else {
            foreach ($articles as $article) {
                $articleCards .= $this->articleCard($article);
            }
        }

        $content = <<<HTML
<header class="hero text-white py-5">
    <div class="container py-lg-4">
        <span class="badge rounded-pill text-bg-light text-primary mb-3">Hyperf CMS</span>
        <h1 class="display-5 fw-bold mb-3">内容驱动的响应式前台</h1>
        <p class="lead mb-0">栏目与文章从数据库读取，Bootstrap 自适应展示，后台可继续扩展管理接口。</p>
    </div>
</header>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0">最新文章</h2>
    </div>
    <div class="row g-4">
        {$articleCards}
    </div>
</main>
HTML;

        return $response->html($this->view->layout('首页', $content, $categories, activeNav: 'home'));
    }

    public function about(HttpResponseInterface $response): ResponseInterface
    {
        $categories = $this->cmsContent->navCategories();
        $content = <<<'HTML'
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <span class="badge text-bg-primary mb-3">About</span>
            <h1 class="fw-bold mb-3">关于 Hyperf CMS</h1>
            <p class="lead text-secondary">前台模块与 MineAdmin 后台、API 分层运行。栏目与文章通过 <code>cms_category</code>、<code>cms_article</code> 表管理，页面使用 Bootstrap 5 自适应布局。</p>
        </div>
    </div>
</main>
HTML;

        return $response->html($this->view->layout('关于我们', $content, $categories, activeNav: 'about'));
    }

    public function contact(HttpResponseInterface $response): ResponseInterface
    {
        $categories = $this->cmsContent->navCategories();
        $content = <<<'HTML'
<main class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5 h-100">
                <h1 class="fw-bold mb-3">联系我们</h1>
                <p class="text-secondary mb-4">欢迎通过以下方式联系，或在此页面后续接入留言表单接口。</p>
                <div class="vstack gap-3">
                    <div><strong>邮箱：</strong> contact@example.com</div>
                    <div><strong>电话：</strong> 400-000-0000</div>
                    <div><strong>地址：</strong> 中国 · 深圳</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <form class="bg-white rounded-4 shadow-sm p-4 p-lg-5 h-100">
                <div class="mb-3">
                    <label class="form-label" for="name">姓名</label>
                    <input class="form-control" id="name" type="text" placeholder="请输入姓名">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">邮箱</label>
                    <input class="form-control" id="email" type="email" placeholder="请输入邮箱">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="message">留言</label>
                    <textarea class="form-control" id="message" rows="5" placeholder="请输入留言内容"></textarea>
                </div>
                <button class="btn btn-primary w-100" type="button">提交留言</button>
            </form>
        </div>
    </div>
</main>
HTML;

        return $response->html($this->view->layout('联系我们', $content, $categories, activeNav: 'contact'));
    }

    private function articleCard(CmsArticle $article): string
    {
        $title = $this->view->e($article->title);
        $summary = $this->view->e($article->summary ?: '暂无摘要');
        $href = '/cms/article/' . $this->view->e($article->slug);
        $date = $this->view->e($this->view->formatDate($article->published_at));
        $category = $this->view->e($article->category?->name ?? '未分类');

        return <<<HTML
<div class="col-md-6 col-lg-4">
    <article class="card h-100 border-0 shadow-sm article-card">
        <div class="card-body p-4 d-flex flex-column">
            <span class="badge text-bg-light text-primary align-self-start mb-2">{$category}</span>
            <h3 class="h5 fw-bold"><a href="{$href}" class="text-decoration-none stretched-link">{$title}</a></h3>
            <p class="text-secondary flex-grow-1">{$summary}</p>
            <small class="text-secondary">{$date}</small>
        </div>
    </article>
</div>
HTML;
    }
}
