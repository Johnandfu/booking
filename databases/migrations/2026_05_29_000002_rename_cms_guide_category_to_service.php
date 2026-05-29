<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class RenameCmsGuideCategoryToService extends Migration
{
    public function up(): void
    {
        Db::table('cms_category')
            ->where('slug', 'guide')
            ->update([
                'name' => '服务项目',
                'slug' => 'service',
                'description' => '我们为业主、租客与旅客提供的服务',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function down(): void
    {
        Db::table('cms_category')
            ->where('slug', 'service')
            ->update([
                'name' => '使用指南',
                'slug' => 'guide',
                'description' => 'CMS 使用与部署说明',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
