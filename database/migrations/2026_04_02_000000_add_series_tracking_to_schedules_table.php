<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'series_id')) {
                $table->unsignedBigInteger('series_id')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('schedules', 'parent_schedule_id')) {
                $table->unsignedBigInteger('parent_schedule_id')->nullable()->after('series_id')->index();
            }

            if (!Schema::hasColumn('schedules', 'is_series_parent')) {
                $table->boolean('is_series_parent')->default(false)->after('parent_schedule_id');
            }

            if (!Schema::hasColumn('schedules', 'expand_to_month')) {
                $table->boolean('expand_to_month')->default(false)->after('is_series_parent');
            }
        });

        if (Schema::hasTable('schedules') && !Schema::hasColumn('schedules', 'parent_schedule_id')) {
            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('parent_schedule_id')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'parent_schedule_id')) {
                $table->dropForeign(['parent_schedule_id']);
            }

            if (Schema::hasColumn('schedules', 'expand_to_month')) {
                $table->dropColumn('expand_to_month');
            }

            if (Schema::hasColumn('schedules', 'is_series_parent')) {
                $table->dropColumn('is_series_parent');
            }

            if (Schema::hasColumn('schedules', 'parent_schedule_id')) {
                $table->dropColumn('parent_schedule_id');
            }

            if (Schema::hasColumn('schedules', 'series_id')) {
                $table->dropColumn('series_id');
            }
        });
    }
};
