<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('class_group_id')
                ->nullable()
                ->constrained('class_groups')
                ->nullOnDelete()
                ->after('class_id');
        });

        // Auto-link existing schedules to class groups by matching label
        $groups = DB::table('class_groups')->get();
        foreach ($groups as $group) {
            $normalized = preg_replace('/[^0-9]/', '', $group->name);
            if (!$normalized) continue;

            $pattern = '%Mix Class%' . $normalized . '%';
            DB::table('schedules')
                ->where('schedule_label', 'LIKE', "%Mix Class ({$normalized})%")
                ->update(['class_group_id' => $group->id]);
        }
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['class_group_id']);
            $table->dropColumn('class_group_id');
        });
    }
};
