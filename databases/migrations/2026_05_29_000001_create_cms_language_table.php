<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsLanguageTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_language', static function (Blueprint $table) {
            $table->comment('CMS 前台多语言配置表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('group_type', 50)->index()->default('main')->comment('分组');
            $table->string('key', 100)->comment('配置键');
            $table->string('title', 100)->default('')->nullable()->comment('配置说明');
            $table->string('language_code', 20)->default('en')->index()->comment('language:en,zh-CN,zh-TW');
            $table->text('value')->nullable()->comment('文案');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->datetimes();
            $table->unique(['group_type', 'key', 'language_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_language');
    }
}
