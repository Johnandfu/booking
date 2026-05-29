<?php

declare(strict_types=1);

/** @var \App\Http\Cms\Support\CmsView $view */
/** @var \Hyperf\Collection\Collection $categories */
/** @var string $title */
/** @var string $content */
/** @var null|string $description */
/** @var null|string $activeNav */

$year = date('Y');
$pageTitle = $view->e($title);
$metaDescription = $view->e($description ?? '基于 Hyperf 的响应式 CMS 前台');
$navHtml = $view->renderNav($categories, $activeNav);
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> - Hyperf CMS</title>
    <meta name="description" content="<?= $metaDescription ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .hero { background: linear-gradient(135deg, #0d6efd, #6610f2); }
        .feature-card, .article-card { transition: transform .2s ease, box-shadow .2s ease; }
        .feature-card:hover, .article-card:hover { transform: translateY(-4px); box-shadow: 0 1rem 2rem rgba(15, 23, 42, .12); }
        .article-content img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/cms">Hyperf CMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmsNavbar" aria-controls="cmsNavbar" aria-expanded="false" aria-label="切换导航">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="cmsNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?= $navHtml ?>
            </ul>
        </div>
    </div>
</nav>

<?= $content ?>

<footer class="border-top bg-white py-4 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-2 text-secondary small">
        <span>© <?= $year ?> Hyperf CMS. All rights reserved.</span>
        <span>Powered by Hyperf and Bootstrap.</span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
