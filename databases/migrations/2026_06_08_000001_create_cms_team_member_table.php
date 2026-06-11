<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateCmsTeamMemberTable extends Migration
{
    public function up(): void
    {
        Schema::create('cms_team_member', static function (Blueprint $table) {
            $table->comment('CMS 团队成员表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('name', 100)->comment('姓名');
            $table->string('position', 100)->nullable()->comment('职位');
            $table->string('phone', 50)->nullable()->comment('联系电话');
            $table->string('avatar', 500)->nullable()->comment('头像');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->datetimes();
            $table->index(['status', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_team_member');
    }
}
