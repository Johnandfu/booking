<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsArticleTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_article', static function (Blueprint $table) {
            $table->comment('CMS 文章表');
            $table->bigIncrements('id')->comment('主键');
            $table->unsignedBigInteger('category_id')->comment('栏目 ID');
            $table->string('title', 200)->comment('标题');
            $table->string('slug', 200)->comment('URL 别名')->unique();
            $table->string('summary', 500)->nullable()->comment('摘要');
            $table->longText('content')->nullable()->comment('正文');
            $table->string('cover', 500)->nullable()->comment('封面图');
            $table->string('author', 50)->nullable()->comment('作者');
            $table->unsignedInteger('views')->default(0)->comment('浏览量');
            $table->tinyInteger('status')->default(1)->comment('状态:0=草稿,1=已发布');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->string('seo_title', 200)->nullable()->comment('SEO 标题');
            $table->string('seo_description', 500)->nullable()->comment('SEO 描述');
            $table->datetimes();
            $table->index('category_id');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_article');
    }
}
