<?php

declare(strict_types=1);

use App\Model\Cms\CmsArticle;
use App\Model\Cms\CmsCategory;
use Hyperf\Database\Seeders\Seeder;

class CmsContentSeeder20260528 extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $news = CmsCategory::firstOrCreate(['slug' => 'news'], [
            'name' => '新闻动态',
            'description' => '公司新闻与产品动态',
            'sort' => 0,
            'status' => CmsCategory::STATUS_ENABLED,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $service = CmsCategory::firstOrCreate(['slug' => 'service'], [
            'name' => '服务',
            'description' => 'CMS 服务说明',
            'sort' => 1,
            'status' => CmsCategory::STATUS_ENABLED,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        CmsArticle::firstOrCreate(['slug' => 'hyperf-cms-launch'], [
            'category_id' => $news->id,
            'title' => 'Hyperf CMS 前台模块上线',
            'summary' => '基于 Hyperf 与 Bootstrap 的响应式前台已可用，支持栏目与文章展示。',
            'content' => '<p>欢迎使用 Hyperf CMS。前台模块提供首页、栏目列表与文章详情，并与现有 MineAdmin 后台分层部署。</p><p>你可以在后台继续扩展文章管理接口，或直接使用数据库维护内容。</p>',
            'cover' => null,
            'author' => '系统',
            'views' => 0,
            'sort' => 100,
            'status' => CmsArticle::STATUS_PUBLISHED,
            'published_at' => $now,
            'seo_title' => 'Hyperf CMS 前台上线',
            'seo_description' => 'Hyperf CMS 响应式前台模块说明',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        CmsArticle::firstOrCreate(['slug' => 'cms-getting-started'], [
            'category_id' => $service->id,
            'title' => '如何初始化 CMS 示例数据',
            'summary' => '执行数据库迁移与 Seeder 即可看到示例栏目和文章。',
            'content' => '<ol><li>执行 <code>php bin/hyperf.php migrate</code></li><li>执行 <code>php bin/hyperf.php db:seed</code> 或导入本 Seeder</li><li>访问 <code>/cms</code> 查看前台效果</li></ol>',
            'cover' => null,
            'author' => '系统',
            'views' => 0,
            'sort' => 90,
            'status' => CmsArticle::STATUS_PUBLISHED,
            'published_at' => $now,
            'seo_title' => 'CMS 快速开始',
            'seo_description' => 'Hyperf CMS 初始化与访问说明',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
