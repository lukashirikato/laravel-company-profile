<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // schedule mode determines how members pick schedules
            if (!Schema::hasColumn('packages', 'schedule_mode')) {
                $table->string('schedule_mode')
                      ->nullable()
                      ->default('booking')
                      ->after('duration_days');
            }

            // default schedule is only meaningful when schedule_mode is locked
            if (!Schema::hasColumn('packages', 'default_schedule_id')) {
                $table->unsignedBigInteger('default_schedule_id')
                      ->nullable()
                      ->after('schedule_mode');

                // add foreign key so updates of schedules cascade properly
                $table->foreign('default_schedule_id')
                      ->references('id')
                      ->on('schedules')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'default_schedule_id')) {
                $table->dropForeign(['default_schedule_id']);
                $table->dropColumn('default_schedule_id');
            }

            if (Schema::hasColumn('packages', 'schedule_mode')) {
                $table->dropColumn('schedule_mode');
            }
        });
    }
};
