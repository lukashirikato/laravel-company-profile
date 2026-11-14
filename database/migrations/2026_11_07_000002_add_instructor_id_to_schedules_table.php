<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('schedules', 'instructor_id')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->foreignId('instructor_id')
                    ->nullable()
                    ->after('instructor')
                    ->constrained('instructors')
                    ->nullOnDelete();
            });
        }

        $names = DB::table('schedules')
            ->whereNotNull('instructor')
            ->where('instructor', '<>', '')
            ->distinct()
            ->pluck('instructor');

        foreach ($names as $rawName) {
            $name = trim((string) $rawName);

            if ($name === '') {
                continue;
            }

            $instructorId = DB::table('instructors')->where('name', $name)->value('id');

            if (! $instructorId) {
                $instructorId = DB::table('instructors')->insertGetId([
                    'name' => $name,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('schedules')
                ->where('instructor', $rawName)
                ->whereNull('instructor_id')
                ->update(['instructor_id' => $instructorId]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('schedules', 'instructor_id')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropForeign(['instructor_id']);
                $table->dropColumn('instructor_id');
            });
        }
    }
};
