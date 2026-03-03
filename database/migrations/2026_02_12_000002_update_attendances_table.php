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
        Schema::table('attendances', function (Blueprint $table) {
            // Tambah schedule_id untuk track jadwal mana yang di-attend
            $table->unsignedBigInteger('schedule_id')->nullable()->after('program');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('set null');
            
            // Update status: checkin_type untuk track cara checkin (qr, manual, etc)
            $table->enum('check_in_type', ['qr', 'manual', 'app'])->default('manual')->after('program');
            
            // Status attendance: present, absent, late, etc
            $table->enum('attendance_status', ['present', 'late', 'absent', 'excused'])->default('present')->after('check_out_at');
            
            // Kuota yang dikurangi saat checkin
            $table->integer('quota_deducted')->default(1)->after('attendance_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn(['schedule_id', 'check_in_type', 'attendance_status', 'quota_deducted']);
        });
    }
};
