<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsTestimonialTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_testimonial', static function (Blueprint $table) {
            $table->comment('CMS 客户反馈表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('name', 100)->comment('客户姓名');
            $table->string('position', 100)->nullable()->comment('职位');
            $table->string('avatar', 500)->nullable()->comment('头像');
            $table->text('content')->nullable()->comment('反馈内容');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->datetimes();
            $table->index(['status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_testimonial');
    }
}
