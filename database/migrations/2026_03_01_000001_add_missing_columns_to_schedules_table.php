<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // ✅ Tambah kolom class_id untuk relasi dengan ClassModel
            if (!Schema::hasColumn('schedules', 'class_id')) {
                $table->foreignId('class_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('class_models')
                    ->onDelete('set null');
            }

            // ✅ Tambah kolom schedule_label untuk label custom jadwal
            if (!Schema::hasColumn('schedules', 'schedule_label')) {
                $table->string('schedule_label')->nullable()->after('class_id')->comment('Custom label for schedule (e.g., "Mix Class 1")');
            }

            // ⚠️ DEPRECATED: package_id sudah tidak dipakai, gunakan packages pivot instead
            // Tapi tetap tambah untuk backward compatibility jika ada data lama
            if (!Schema::hasColumn('schedules', 'package_id')) {
                $table->foreignId('package_id')
                    ->nullable()
                    ->after('schedule_label')
                    ->constrained('packages')
                    ->onDelete('set null')
                    ->comment('DEPRECATED: Use packages pivot table instead. This is for backward compatibility only.');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'package_id')) {
                $table->dropForeignKeyIfExists(['package_id']);
                $table->dropColumn('package_id');
            }

            if (Schema::hasColumn('schedules', 'schedule_label')) {
                $table->dropColumn('schedule_label');
            }

            if (Schema::hasColumn('schedules', 'class_id')) {
                $table->dropForeignKeyIfExists(['class_id']);
                $table->dropColumn('class_id');
            }
        });
    }
};
