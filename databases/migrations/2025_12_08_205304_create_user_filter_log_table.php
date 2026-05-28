<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateUserFilterLogTable extends  Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_filter_logs', static function (Blueprint $table) {
            $table->comment('用户滤镜日志表');
            $table->bigIncrements('id')->comment('主键');
            $table->unsignedInteger('filter_id')->index()->nullable()->comment('滤镜ID');
            $table->unsignedInteger('use_second')->default(0)->comment('耗时秒');
            $table->ipAddress('ip_address')->nullable()->comment('ip');
            $table->string('os', 255)->nullable()->comment('操作系统');
            $table->string('platform_id', 255)->nullable()->index()->comment('商品ID');
            $table->string('browser', 255)->nullable()->comment('浏览器');
            $table->string('remark', 2000)->nullable()->comment('备注');
            $table->string('origin_image', 255)->nullable()->comment('原图');
            $table->string('filter_image', 255)->nullable()->comment('滤镜图');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_filter_logs');
    }
};
