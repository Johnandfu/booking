<?php

declare(strict_types=1);

/** @var \App\Http\Cms\Support\CmsView $view */
/** @var \App\Model\Cms\CmsArticle $article */

$category = $article->category;
?>
<main class="container py-5">
    <article class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
                <?php if ($category): ?>
                    <a href="/cms/category/<?= $view->e($category->slug) ?>" class="badge text-bg-primary text-decoration-none mb-3"><?= $view->e($category->name) ?></a>
                <?php endif; ?>
                <h1 class="fw-bold mb-3"><?= $view->e($article->title) ?></h1>
                <div class="d-flex flex-wrap gap-3 text-secondary small mb-4 pb-3 border-bottom">
                    <span>作者：<?= $view->e($article->author ?? '管理员') ?></span>
                    <span>发布：<?= $view->e($view->formatDate($article->published_at)) ?></span>
                    <span>阅读：<?= (int) $article->views + 1 ?></span>
                </div>
                <?php if ($article->cover): ?>
                    <img src="<?= $view->e($article->cover) ?>" class="img-fluid rounded-3 mb-4" alt="<?= $view->e($article->title) ?>">
                <?php endif; ?>
                <div class="article-content"><?= $article->content ?: '<p class="text-secondary">暂无正文</p>' ?></div>
            </div>
        </div>
    </article>
</main>
