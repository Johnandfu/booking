<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsCategoryTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_category', static function (Blueprint $table) {
            $table->comment('CMS 栏目表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('name', 100)->comment('栏目名称');
            $table->string('slug', 100)->comment('URL 别名')->unique();
            $table->string('image', 500)->nullable()->comment('栏目图片');
            $table->string('description', 500)->nullable()->comment('栏目描述');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->index()->default(1)->comment('状态:0=禁用,1=启用');
            $table->datetimes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_category');
    }
}
