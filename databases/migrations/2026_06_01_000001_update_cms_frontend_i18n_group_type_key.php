<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

class UpdateCmsFrontendI18nGroupTypeKey extends Migration
{
    public function up(): void
    {
        Schema::table('cms_frontend_i18n', static function (Blueprint $table): void {
            if (! Schema::hasColumn('cms_frontend_i18n', 'group_type')) {
                $table->string('group_type', 50)->default('main')->after('key')->comment('分组');
            }
        });

        if (Schema::hasColumn('cms_frontend_i18n', 'group_name')) {
            Db::statement('UPDATE cms_frontend_i18n SET group_type = group_name WHERE group_type = "main"');
        }

        $this->dropIndexIfExists('cms_frontend_i18n_key_unique');
        $this->dropIndexIfExists('cms_frontend_i18n_group_name_status_index');
        $this->dropIndexIfExists('cms_frontend_i18n_group_type_key_unique');
        $this->dropIndexIfExists('cms_frontend_i18n_group_type_status_index');

        Schema::table('cms_frontend_i18n', static function (Blueprint $table): void {
            if (Schema::hasColumn('cms_frontend_i18n', 'group_name')) {
                $table->dropColumn('group_name');
            }
        });

        Schema::table('cms_frontend_i18n', static function (Blueprint $table): void {
            $table->unique(['group_type', 'key']);
            $table->index(['group_type', 'status']);
        });
    }

    public function down(): void
    {
        $this->dropIndexIfExists('cms_frontend_i18n_group_type_key_unique');
        $this->dropIndexIfExists('cms_frontend_i18n_group_type_status_index');

        Schema::table('cms_frontend_i18n', static function (Blueprint $table): void {
            if (! Schema::hasColumn('cms_frontend_i18n', 'group_name')) {
                $table->string('group_name', 50)->default('common')->after('key')->comment('分组');
            }
            $table->unique('key');
            $table->index(['group_name', 'status']);
        });
    }

    private function dropIndexIfExists(string $index): void
    {
        try {
            Db::statement(sprintf('ALTER TABLE cms_frontend_i18n DROP INDEX `%s`', $index));
        } catch (Throwable) {
        }
    }
}
