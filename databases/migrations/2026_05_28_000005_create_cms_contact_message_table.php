<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsContactMessageTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_contact_message', static function (Blueprint $table) {
            $table->comment('CMS 留言表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('name', 100)->comment('姓名');
            $table->string('email', 200)->nullable()->comment('邮箱');
            $table->string('phone', 50)->nullable()->comment('电话');
            $table->string('content', 2000)->comment('留言内容');
            $table->string('ip', 64)->nullable()->comment('IP');
            $table->string('user_agent', 500)->nullable()->comment('浏览器 UA');
            $table->tinyInteger('status')->default(0)->comment('状态:0=未处理,1=已处理');
            $table->datetimes();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contact_message');
    }
}
