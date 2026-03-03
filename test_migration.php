<?php
// Test script untuk verify column remaining_classes exist at orders table

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if column exists
if (Schema::hasColumn('orders', 'remaining_classes')) {
    echo "✅ Column 'remaining_classes' successfully created in 'orders' table!\n";
    
    // Get column details
    $columns = DB::select("
        SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'orders' 
        AND COLUMN_NAME IN ('remaining_quota', 'remaining_classes')
    ");
    
    echo "\nColumn details:\n";
    foreach ($columns as $col) {
        echo "  - {$col->COLUMN_NAME}: {$col->DATA_TYPE} (nullable: {$col->IS_NULLABLE})\n";
    }
    
    // Check existing orders
    $orderCount = DB::table('orders')->count();
    echo "\nExisting orders: $orderCount\n";
    
    if ($orderCount > 0) {
        $sample = DB::table('orders')->first();
        echo "\nSample order (ID: {$sample->id}):\n";
        echo "  - remaining_quota: {$sample->remaining_quota}\n";
        echo "  - remaining_classes: {$sample->remaining_classes}\n";
    }
} else {
    echo "❌ Column 'remaining_classes' NOT found!\n";
    exit(1);
}
?>
