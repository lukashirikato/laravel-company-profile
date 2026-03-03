<?php
// Test script to verify Schedule model accessor

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\Schedule;

$schedule = Schedule::with('packages')->first();

if ($schedule) {
    echo "Schedule ID: " . $schedule->id . "\n";
    echo "Label: " . $schedule->schedule_label . "\n";
    echo "Package Names: " . $schedule->packageNames . "\n";
    echo "Packages count: " . $schedule->packages->count() . "\n";
} else {
    echo "No schedule found\n";
}
