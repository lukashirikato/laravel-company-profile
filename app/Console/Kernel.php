<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // ========================================
        // AUTO-EXPIRE PACKAGES
        // ========================================
        // Jalankan setiap hari jam 00:00 untuk mengupdate status paket yang sudah expired
        $schedule->command('packages:auto-expire')
            ->daily()
            ->at('00:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                \Log::info('✅ Auto-expire packages berhasil dijalankan');
            })
            ->onFailure(function () {
                \Log::error('❌ Auto-expire packages gagal dijalankan');
            });

        // ========================================
        // REMINDER PAKET AKAN EXPIRED (OPTIONAL)
        // ========================================
        // Kirim notifikasi untuk paket yang akan expired dalam 3 hari
        // Uncomment jika sudah ada command untuk reminder
        /*
        $schedule->command('packages:send-expiring-reminder')
            ->dailyAt('09:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();
        */

        // ========================================
        // CLEANUP DATA LAMA (OPTIONAL)
        // ========================================
        // Hapus log atau temporary data setiap minggu
        /*
        $schedule->command('logs:clean')
            ->weekly()
            ->sundays()
            ->at('01:00')
            ->timezone('Asia/Jakarta');
        */

        // ========================================
        // BACKUP DATABASE (OPTIONAL)
        // ========================================
        // Backup database setiap hari jam 02:00
        /*
        $schedule->command('backup:run')
            ->daily()
            ->at('02:00')
            ->timezone('Asia/Jakarta')
            ->onSuccess(function () {
                \Log::info('✅ Database backup berhasil');
            })
            ->onFailure(function () {
                \Log::error('❌ Database backup gagal');
            });
        */
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}