<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsFrontendI18nTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_frontend_i18n', static function (Blueprint $table) {
            $table->comment('CMS 前台多语言配置表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('group_type', 50)->default('main')->comment('分组');
            $table->string('key', 100)->comment('配置键');
            $table->string('title', 100)->comment('配置说明');
            $table->text('en')->nullable()->comment('英文文案');
            $table->text('zh')->nullable()->comment('中文文案');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->datetimes();
            $table->unique(['group_type', 'key']);
            $table->index(['group_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_frontend_i18n');
    }
}
