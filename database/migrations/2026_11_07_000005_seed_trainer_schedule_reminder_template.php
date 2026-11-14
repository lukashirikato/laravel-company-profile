<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('whatsapp_templates')
            ->where('key', 'trainer_schedule_reminder')
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('whatsapp_templates')->insert([
            'key' => 'trainer_schedule_reminder',
            'name' => 'Reminder Jadwal Pelatih',
            'description' => 'Dikirim otomatis H-1 kepada pelatih berisi daftar sesi mengajar besok.',
            'placeholders' => json_encode([
                'trainer_name' => 'Nama pelatih',
                'date' => 'Tanggal sesi (contoh: Senin, 10 November 2026)',
                'schedule_details' => 'Daftar sesi mengajar',
                'total_sessions' => 'Jumlah sesi',
                'portal_url' => 'URL portal pelatih',
            ]),
            'message' => "Halo {trainer_name},\n\nJadwal mengajar besok, {date}:\n\n{schedule_details}\n\nTotal sesi: {total_sessions}\n\nBuka portal pelatih: {portal_url}",
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('whatsapp_templates')->where('key', 'trainer_schedule_reminder')->delete();
    }
};
