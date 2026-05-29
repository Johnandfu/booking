<?php

declare(strict_types=1);

/** @var \App\Http\Cms\Support\CmsView $view */
/** @var \App\Model\Cms\CmsPage $page */
?>
<main class="container py-5">
    <article class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
                <h1 class="fw-bold mb-4"><?= $view->e($page->title) ?></h1>
                <div class="article-content"><?= $page->content ?: '<p class="text-secondary">暂无内容</p>' ?></div>
            </div>
        </div>
    </article>
</main>
