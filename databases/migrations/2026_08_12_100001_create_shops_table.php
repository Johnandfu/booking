<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateShopsTable extends Migration
{
    public function up(): void
    {
        Schema::create('shops', static function (Blueprint $table) {
            $table->comment('SHOPLINE 店铺');
            $table->bigIncrements('id');
            $table->string('handle', 128)->comment('店铺 handle（域名前缀）');
            $table->string('domain', 255)->default('')->comment('店铺域名，如 handle.myshopline.com');
            $table->string('timezone', 64)->default('UTC')->comment('店铺时区（IANA），计算用；库内时间仍存 UTC');
            $table->string('locale', 32)->default('')->comment('商家后台语言（安装回调 lang）');
            $table->tinyInteger('status')->default(1)->comment('1=已安装 2=已卸载/停用');
            $table->timestamp('installed_at')->nullable()->comment('首次授权成功时间 UTC');
            $table->datetimes();

            $table->unique('handle');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
}
