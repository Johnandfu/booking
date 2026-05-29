<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsBannerTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_banner', static function (Blueprint $table) {
            $table->comment('CMS 轮播图表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('title', 200)->comment('标题');
            $table->string('image', 500)->comment('图片地址');
            $table->string('link', 500)->nullable()->comment('跳转链接');
            $table->string('description', 500)->nullable()->comment('描述');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->datetimes();
            $table->index(['status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_banner');
    }
}
