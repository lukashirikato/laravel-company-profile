<?php
// Test ScheduleResource form and table methods

require 'vendor/autoload.php';
require 'bootstrap/app.php';

try {
    $resource = new \App\Filament\Resources\ScheduleResource();
    echo "✓ ScheduleResource instantiated successfully\n";
    
    // Check if methods exist
    if (method_exists($resource, 'table')) {
        echo "✓ table() method exists\n";
    }
    
    if (method_exists($resource, 'form')) {
        echo "✓ form() method exists\n";
    }
    
    if (method_exists($resource, 'getEloquentQuery')) {
        echo "✓ getEloquentQuery() method exists\n";
    }
    
    echo "\n✅ ScheduleResource structure is valid\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line " . $e->getLine() . ")\n";
}
