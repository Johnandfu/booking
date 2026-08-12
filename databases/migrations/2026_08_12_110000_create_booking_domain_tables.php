<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateBookingDomainTables extends Migration
{
    public function up(): void
    {
        Schema::create('booking_services', static function (Blueprint $table) {
            $table->comment('预约服务');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->string('name', 128)->comment('服务名称');
            $table->string('description', 1000)->default('')->comment('服务描述');
            $table->unsignedInteger('duration_minutes')->comment('服务时长（分钟）');
            $table->unsignedInteger('capacity')->default(1)->comment('单时段容量');
            $table->unsignedInteger('buffer_minutes')->default(0)->comment('缓冲时间（分钟）');
            $table->tinyInteger('status')->default(1)->comment('1=启用 2=停用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('shopline_product_id', 64)->default('')->comment('SHOPLINE 商品 ID（后续关联）');
            $table->datetimes();

            $table->index('shop_id');
            $table->index(['shop_id', 'status']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_locations', static function (Blueprint $table) {
            $table->comment('预约门店/地点');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->string('name', 128)->comment('门店名称');
            $table->string('address', 255)->default('')->comment('地址');
            $table->string('phone', 32)->default('')->comment('电话');
            $table->tinyInteger('status')->default(1)->comment('1=启用 2=停用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->datetimes();

            $table->index('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_staff', static function (Blueprint $table) {
            $table->comment('预约员工');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->string('name', 128)->comment('员工姓名');
            $table->string('email', 128)->default('')->comment('邮箱');
            $table->string('phone', 32)->default('')->comment('电话');
            $table->tinyInteger('status')->default(1)->comment('1=启用 2=停用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->datetimes();

            $table->index('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_staff_service', static function (Blueprint $table) {
            $table->comment('员工-服务关联');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_id')->comment('booking_staff.id');
            $table->unsignedBigInteger('service_id')->comment('booking_services.id');

            $table->unique(['staff_id', 'service_id']);
            $table->foreign('staff_id')->references('id')->on('booking_staff')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('booking_services')->onDelete('cascade');
        });

        Schema::create('booking_staff_location', static function (Blueprint $table) {
            $table->comment('员工-门店关联');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_id')->comment('booking_staff.id');
            $table->unsignedBigInteger('location_id')->comment('booking_locations.id');

            $table->unique(['staff_id', 'location_id']);
            $table->foreign('staff_id')->references('id')->on('booking_staff')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('booking_locations')->onDelete('cascade');
        });

        Schema::create('booking_schedules', static function (Blueprint $table) {
            $table->comment('员工排班');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->unsignedBigInteger('staff_id')->comment('booking_staff.id');
            $table->unsignedBigInteger('location_id')->nullable()->comment('booking_locations.id，null=全部门店');
            $table->tinyInteger('weekday')->comment('0=Sun .. 6=Sat');
            $table->time('start_time')->comment('开始时间（门店本地时区语义，存 time）');
            $table->time('end_time')->comment('结束时间（门店本地时区语义，存 time）');
            $table->tinyInteger('status')->default(1)->comment('1=启用 2=停用');
            $table->datetimes();

            $table->index(['shop_id', 'staff_id', 'weekday']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_slots', static function (Blueprint $table) {
            $table->comment('可预约时段');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->unsignedBigInteger('service_id')->comment('booking_services.id');
            $table->unsignedBigInteger('location_id')->comment('booking_locations.id');
            $table->unsignedBigInteger('staff_id')->comment('booking_staff.id');
            $table->timestamp('start_at')->comment('开始时间 UTC');
            $table->timestamp('end_at')->comment('结束时间 UTC');
            $table->unsignedInteger('capacity')->comment('容量');
            $table->unsignedInteger('booked')->default(0)->comment('已预约数');
            $table->string('status', 16)->default('available')->comment('available|locked|full|disabled');
            $table->datetimes();

            $table->unique(['shop_id', 'service_id', 'location_id', 'staff_id', 'start_at'], 'booking_slots_unique_slot');
            $table->index(['shop_id', 'start_at', 'status']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_slot_locks', static function (Blueprint $table) {
            $table->comment('时段锁');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->unsignedBigInteger('slot_id')->comment('booking_slots.id');
            $table->string('lock_token', 64)->comment('锁令牌');
            $table->string('customer_key', 128)->default('')->comment('客户标识');
            $table->timestamp('expire_at')->comment('过期时间 UTC');
            $table->string('status', 16)->default('active')->comment('active|released|consumed|expired');
            $table->datetimes();

            $table->unique('lock_token');
            $table->index(['slot_id', 'status', 'expire_at']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('bookings', static function (Blueprint $table) {
            $table->comment('预约单');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->string('booking_no', 32)->comment('预约单号');
            $table->string('status', 16)->default('pending')->comment('pending|confirmed|cancelled|completed|no_show|expired');
            $table->string('customer_name', 128)->default('')->comment('客户姓名');
            $table->string('customer_email', 128)->default('')->comment('客户邮箱');
            $table->string('customer_phone', 32)->default('')->comment('客户电话');
            $table->unsignedBigInteger('service_id')->comment('booking_services.id');
            $table->unsignedBigInteger('location_id')->comment('booking_locations.id');
            $table->unsignedBigInteger('staff_id')->comment('booking_staff.id');
            $table->unsignedBigInteger('slot_id')->comment('booking_slots.id');
            $table->timestamp('start_at')->comment('开始时间 UTC');
            $table->timestamp('end_at')->comment('结束时间 UTC');
            $table->string('lock_token', 64)->default('')->comment('关联锁令牌');
            $table->string('idempotency_key', 64)->nullable()->comment('幂等键，NULL 表示未使用');
            $table->string('shopline_order_id', 64)->default('')->comment('SHOPLINE 订单 ID');
            $table->string('remark', 500)->default('')->comment('备注');
            $table->timestamp('cancelled_at')->nullable()->comment('取消时间 UTC');
            $table->datetimes();

            $table->unique('booking_no');
            $table->unique(['shop_id', 'idempotency_key'], 'bookings_shop_idempotency_unique');
            $table->index(['shop_id', 'status', 'start_at']);
            $table->index('slot_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_items', static function (Blueprint $table) {
            $table->comment('预约明细');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id')->comment('bookings.id');
            $table->unsignedBigInteger('service_id')->comment('booking_services.id');
            $table->string('title', 128)->comment('标题快照');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->json('meta')->nullable()->comment('扩展元数据');
            $table->datetimes();

            $table->index('booking_id');
        });

        Schema::create('booking_holidays', static function (Blueprint $table) {
            $table->comment('店铺节假日');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->date('holiday_date')->comment('假日日期（店铺本地日）');
            $table->string('name', 128)->default('')->comment('假日名称');
            $table->tinyInteger('status')->default(1)->comment('1=启用 2=停用');
            $table->datetimes();

            $table->unique(['shop_id', 'holiday_date']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('booking_blackouts', static function (Blueprint $table) {
            $table->comment('不可预约时段');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('shops.id');
            $table->unsignedBigInteger('staff_id')->nullable()->comment('booking_staff.id，null=全部员工');
            $table->unsignedBigInteger('location_id')->nullable()->comment('booking_locations.id，null=全部门店');
            $table->timestamp('start_at')->comment('开始时间 UTC');
            $table->timestamp('end_at')->comment('结束时间 UTC');
            $table->string('reason', 255)->default('')->comment('原因');
            $table->datetimes();

            $table->index(['shop_id', 'start_at', 'end_at']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('webhook_events', static function (Blueprint $table) {
            $table->comment('Webhook 事件记录');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->nullable()->comment('shops.id');
            $table->string('topic', 128)->comment('事件主题');
            $table->string('event_id', 128)->comment('事件唯一 ID');
            $table->json('payload')->comment('事件载荷');
            $table->string('status', 16)->default('pending')->comment('pending|processing|processed|failed');
            $table->timestamp('processed_at')->nullable()->comment('处理完成时间 UTC');
            $table->datetimes();

            $table->unique('event_id');
            $table->index(['shop_id', 'topic', 'status']);
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('booking_blackouts');
        Schema::dropIfExists('booking_holidays');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('booking_slot_locks');
        Schema::dropIfExists('booking_slots');
        Schema::dropIfExists('booking_schedules');
        Schema::dropIfExists('booking_staff_location');
        Schema::dropIfExists('booking_staff_service');
        Schema::dropIfExists('booking_staff');
        Schema::dropIfExists('booking_locations');
        Schema::dropIfExists('booking_services');
    }
}
