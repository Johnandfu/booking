<?php

declare(strict_types=1);

use App\Model\Cms\CmsArticle;
use App\Model\Cms\CmsCategory;
use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class CmsContentSeeder20260528 extends Seeder
{
    public function run(): void
    {
        if (CmsCategory::query()->exists()) {
            return;
        }

        $now = date('Y-m-d H:i:s');

        $newsId = Db::table('cms_category')->insertGetId([
            'name' => '新闻动态',
            'slug' => 'news',
            'description' => '公司新闻与产品动态',
            'sort' => 100,
            'status' => CmsCategory::STATUS_ENABLED,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $guideId = Db::table('cms_category')->insertGetId([
            'name' => '使用指南',
            'slug' => 'guide',
            'description' => 'CMS 使用与部署说明',
            'sort' => 90,
            'status' => CmsCategory::STATUS_ENABLED,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Db::table('cms_article')->insert([
            [
                'category_id' => $newsId,
                'title' => 'Hyperf CMS 前台模块上线',
                'slug' => 'hyperf-cms-launch',
                'summary' => '基于 Hyperf 与 Bootstrap 的响应式前台已可用，支持栏目与文章展示。',
                'content' => '<p>欢迎使用 Hyperf CMS。前台模块提供首页、栏目列表与文章详情，并与现有 MineAdmin 后台分层部署。</p><p>你可以在后台继续扩展文章管理接口，或直接使用数据库维护内容。</p>',
                'cover' => null,
                'author' => '系统',
                'views' => 0,
                'status' => CmsArticle::STATUS_PUBLISHED,
                'published_at' => $now,
                'seo_title' => 'Hyperf CMS 前台上线',
                'seo_description' => 'Hyperf CMS 响应式前台模块说明',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $guideId,
                'title' => '如何初始化 CMS 示例数据',
                'slug' => 'cms-getting-started',
                'summary' => '执行数据库迁移与 Seeder 即可看到示例栏目和文章。',
                'content' => '<ol><li>执行 <code>php bin/hyperf.php migrate</code></li><li>执行 <code>php bin/hyperf.php db:seed</code> 或导入本 Seeder</li><li>访问 <code>/cms</code> 查看前台效果</li></ol>',
                'cover' => null,
                'author' => '系统',
                'views' => 0,
                'status' => CmsArticle::STATUS_PUBLISHED,
                'published_at' => $now,
                'seo_title' => 'CMS 快速开始',
                'seo_description' => 'Hyperf CMS 初始化与访问说明',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
