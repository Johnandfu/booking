<?php

declare(strict_types=1);

/** @var \App\Http\Cms\Support\CmsView $view */
/** @var \App\Model\Cms\CmsCategory $category */
/** @var \Hyperf\Contract\LengthAwarePaginatorInterface $paginator */
/** @var string $pagination */
?>
<main class="container py-5">
    <div class="mb-4">
        <span class="badge text-bg-primary mb-2">栏目</span>
        <h1 class="fw-bold mb-2"><?= $view->e($category->name) ?></h1>
        <p class="text-secondary mb-0"><?= $view->e($category->description ?? $category->name) ?></p>
    </div>
    <div class="vstack gap-3 mb-4">
        <?php if ($paginator->total() === 0): ?>
            <div class="alert alert-secondary">该栏目暂无文章。</div>
        <?php else: ?>
            <?php foreach ($paginator->items() as $article): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-2"><a href="/cms/article/<?= $view->e($article->slug) ?>" class="text-decoration-none"><?= $view->e($article->title) ?></a></h2>
                        <p class="text-secondary mb-2"><?= $view->e($article->summary ?: '') ?></p>
                        <small class="text-secondary"><?= $view->e($view->formatDate($article->published_at)) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?= $pagination ?>
</main>
