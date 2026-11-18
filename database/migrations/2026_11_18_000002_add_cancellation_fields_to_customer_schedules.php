<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_schedules', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('joined_at');
            }
            if (!Schema::hasColumn('customer_schedules', 'cancelled_by')) {
                $table->string('cancelled_by', 20)->nullable()->after('cancelled_at');
            }
            if (!Schema::hasColumn('customer_schedules', 'cancellation_reason')) {
                $table->string('cancellation_reason', 255)->nullable()->after('cancelled_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customer_schedules', function (Blueprint $table) {
            foreach (['cancellation_reason', 'cancelled_by', 'cancelled_at'] as $column) {
                if (Schema::hasColumn('customer_schedules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
