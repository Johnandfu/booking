<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class AddSortToCmsArticleTable extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cms_article', 'sort')) {
            Schema::table('cms_article', static function (Blueprint $table): void {
                $table->unsignedInteger('sort')->default(0)->after('views')->comment('排序');
                $table->index(['status', 'sort']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cms_article', 'sort')) {
            Schema::table('cms_article', static function (Blueprint $table): void {
                $table->dropIndex(['status', 'sort']);
                $table->dropColumn('sort');
            });
        }
    }
}
