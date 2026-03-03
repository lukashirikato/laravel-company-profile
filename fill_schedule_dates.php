<?php
/**
 * Script untuk mengisi schedule_date pada semua schedule yang belum punya tanggal.
 * Tanggal dihitung berdasarkan kolom 'day' minggu ini (23 Feb - 1 Mar 2026).
 * 
 * Jalankan: php artisan tinker fill_schedule_dates.php
 * Atau:     php fill_schedule_dates.php (dari folder progres)
 */

// Bootstrap Laravel jika dijalankan langsung
if (!function_exists('app')) {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
}

use App\Models\Schedule;
use Carbon\Carbon;

// Monday minggu ini = 23 Feb 2026
$startOfWeek = Carbon::parse('2026-02-23');

$dayMap = [
    'Monday'    => 0,
    'Tuesday'   => 1,
    'Wednesday' => 2,
    'Thursday'  => 3,
    'Friday'    => 4,
    'Saturday'  => 5,
    'Sunday'    => 6,
];

$schedules = Schedule::whereNull('schedule_date')
    ->whereNotNull('day')
    ->get();

echo "Found {$schedules->count()} schedules without date.\n";

$updated = 0;
foreach ($schedules as $schedule) {
    if (isset($dayMap[$schedule->day])) {
        $date = $startOfWeek->copy()->addDays($dayMap[$schedule->day]);
        $schedule->schedule_date = $date;
        $schedule->save();
        $updated++;
        echo "  #{$schedule->id} ({$schedule->schedule_label}) {$schedule->day} => {$date->format('d/M/Y')}\n";
    }
}

echo "\nDone! Updated: {$updated} schedules.\n";
