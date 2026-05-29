<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsMapSettingTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_map_setting', static function (Blueprint $table) {
            $table->comment('CMS 地图配置表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('title', 200)->default('Brisbane, Queensland, Australia')->comment('地图标题');
            $table->string('address', 500)->default('Brisbane QLD Australia')->comment('地址');
            $table->decimal('latitude', 10, 7)->nullable()->comment('纬度');
            $table->decimal('longitude', 10, 7)->nullable()->comment('经度');
            $table->unsignedTinyInteger('zoom')->default(14)->comment('缩放级别');
            $table->datetimes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_map_setting');
    }
}
