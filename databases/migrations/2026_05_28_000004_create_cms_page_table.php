<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsPageTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_page', static function (Blueprint $table) {
            $table->comment('CMS 单页表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('title', 200)->comment('标题');
            $table->string('slug', 200)->comment('URL 别名')->unique();
            $table->longText('content')->nullable()->comment('正文');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->string('seo_title', 200)->nullable()->comment('SEO 标题');
            $table->string('seo_description', 500)->nullable()->comment('SEO 描述');
            $table->datetimes();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_page');
    }
}
