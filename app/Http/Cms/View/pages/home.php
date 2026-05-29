<?php

declare(strict_types=1);

/** @var \App\Http\Cms\Support\CmsView $view */
/** @var \Hyperf\Collection\Collection $articles */
/** @var \Hyperf\Collection\Collection $banners */
?>
<?php if ($banners->isNotEmpty()): ?>
<div id="cmsBanner" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($banners as $index => $banner): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= $view->e($banner->image) ?>" class="d-block w-100" style="max-height:420px;object-fit:cover;" alt="<?= $view->e($banner->title) ?>">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="fw-bold"><?= $view->e($banner->title) ?></h1>
                    <p><?= $view->e($banner->description) ?></p>
                    <?php if ($banner->link): ?>
                        <a class="btn btn-light" href="<?= $view->e($banner->link) ?>">查看详情</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#cmsBanner" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">上一张</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#cmsBanner" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">下一张</span>
    </button>
</div>
<?php else: ?>
<header class="hero text-white py-5">
    <div class="container py-lg-4">
        <span class="badge rounded-pill text-bg-light text-primary mb-3">Hyperf CMS</span>
        <h1 class="display-5 fw-bold mb-3">内容驱动的响应式前台</h1>
        <p class="lead mb-0">栏目、文章、轮播和单页从数据库读取，Bootstrap 自适应展示。</p>
    </div>
</header>
<?php endif; ?>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0">最新文章</h2>
    </div>
    <div class="row g-4">
        <?php if ($articles->isEmpty()): ?>
            <div class="col-12"><div class="alert alert-info mb-0">暂无文章，请先在后台发布内容。</div></div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm article-card">
                        <?php if ($article->cover): ?>
                            <img src="<?= $view->e($article->cover) ?>" class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= $view->e($article->title) ?>">
                        <?php endif; ?>
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="badge text-bg-light text-primary align-self-start mb-2"><?= $view->e($article->category?->name ?? '未分类') ?></span>
                            <h3 class="h5 fw-bold"><a href="/cms/article/<?= $view->e($article->slug) ?>" class="text-decoration-none stretched-link"><?= $view->e($article->title) ?></a></h3>
                            <p class="text-secondary flex-grow-1"><?= $view->e($article->summary ?: '暂无摘要') ?></p>
                            <small class="text-secondary"><?= $view->e($view->formatDate($article->published_at)) ?></small>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
