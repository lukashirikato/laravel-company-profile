<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('customer_schedules', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->nullable()->after('schedule_id');
        $table->enum('status', ['confirmed','cancelled','completed'])
              ->default('confirmed')
              ->after('order_id');
        $table->timestamp('joined_at')->nullable()->after('status');

        $table->unique(['customer_id', 'schedule_id'], 'unique_customer_schedule');
    });
}

public function down()
{
    Schema::table('customer_schedules', function (Blueprint $table) {
        $table->dropUnique('unique_customer_schedule');
        $table->dropColumn(['order_id', 'status', 'joined_at']);
    });
}
};