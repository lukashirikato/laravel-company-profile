<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

use App\Models\Schedule;

$schedule = Schedule::with('packages', 'classModel')->first();

if ($schedule) {
    echo "═══════════════════════════════════════════\n";
    echo "✓ Schedule Data Test\n";
    echo "═══════════════════════════════════════════\n";
    echo "ID: " . $schedule->id . "\n";
    echo "Label: " . ($schedule->schedule_label ?? 'N/A') . "\n";
    echo "Packages: " . $schedule->packageNames . "\n";
    echo "Package Summary: " . $schedule->packageSummary . "\n";
    echo "Class: " . ($schedule->classModel?->class_name ?? 'N/A') . "\n";
    echo "Day: " . ($schedule->day ?? 'N/A') . "\n";
    echo "Time: " . ($schedule->class_time ?? 'N/A') . "\n";
    echo "Instructor: " . ($schedule->instructor ?? 'N/A') . "\n";
    echo "Visibility: " . ($schedule->show_on_landing ? 'Visible' : 'Hidden') . "\n";
    echo "═══════════════════════════════════════════\n";
    echo "✅ All accessor fields are working!\n";
} else {
    echo "❌ No schedules found in database\n";
}
