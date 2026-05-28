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

class AlterUserFilterLogAddModel extends  Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_filter_logs', function (Blueprint $table) {
            $table->string('model', 100)->nullable()->comment('模型');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
