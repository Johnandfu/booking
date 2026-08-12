<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateShopTokensTable extends Migration
{
    public function up(): void
    {
        Schema::create('shop_tokens', static function (Blueprint $table) {
            $table->comment('SHOPLINE 店铺 Access Token');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->text('access_token')->comment('Access Token（禁止写入业务日志）');
            $table->string('scope', 1024)->default('')->comment('授权 scope，逗号分隔');
            $table->timestamp('expire_at')->nullable()->comment('Token 过期时间 UTC');
            $table->datetimes();

            $table->unique('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_tokens');
    }
}
