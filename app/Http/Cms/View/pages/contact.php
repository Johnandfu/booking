<?php

declare(strict_types=1);

/** @var \App\Http\Cms\Support\CmsView $view */
/** @var null|string $message */
?>
<main class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5 h-100">
                <h1 class="fw-bold mb-3">联系我们</h1>
                <p class="text-secondary mb-4">欢迎通过以下方式联系，也可以直接提交留言，后台可查看和处理。</p>
                <div class="vstack gap-3">
                    <div><strong>邮箱：</strong> contact@example.com</div>
                    <div><strong>电话：</strong> 400-000-0000</div>
                    <div><strong>地址：</strong> 中国 · 深圳</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <form class="bg-white rounded-4 shadow-sm p-4 p-lg-5 h-100" method="post" action="/cms/contact">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?= $view->e($message) ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label" for="name">姓名</label>
                    <input class="form-control" id="name" name="name" type="text" placeholder="请输入姓名" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">邮箱</label>
                    <input class="form-control" id="email" name="email" type="email" placeholder="请输入邮箱">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone">电话</label>
                    <input class="form-control" id="phone" name="phone" type="text" placeholder="请输入电话">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">留言</label>
                    <textarea class="form-control" id="content" name="content" rows="5" placeholder="请输入留言内容" required></textarea>
                </div>
                <button class="btn btn-primary w-100" type="submit">提交留言</button>
            </form>
        </div>
    </div>
</main>
