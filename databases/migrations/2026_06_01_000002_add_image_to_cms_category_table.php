<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class AddImageToCmsCategoryTable extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cms_category', 'image')) {
            Schema::table('cms_category', static function (Blueprint $table): void {
                $table->string('image', 500)->nullable()->after('slug')->comment('栏目图片');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cms_category', 'image')) {
            Schema::table('cms_category', static function (Blueprint $table): void {
                $table->dropColumn('image');
            });
        }
    }
}
