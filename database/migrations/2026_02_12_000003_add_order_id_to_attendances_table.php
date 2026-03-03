<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('customer_id');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('attendances', 'check_in_time')) {
                $table->datetime('check_in_time')->nullable()->after('check_in_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            
            if (Schema::hasColumn('attendances', 'check_in_time')) {
                $table->dropColumn('check_in_time');
            }
        });
    }
};
