<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\Package;

echo "=== Fix Exclusive Package remaining_classes ===\n\n";

// Cari semua package exclusive
$exclusivePackageIds = Package::where('is_exclusive', true)->pluck('id');

echo "Exclusive package IDs: " . $exclusivePackageIds->implode(', ') . "\n";

if ($exclusivePackageIds->isEmpty()) {
    echo "Tidak ada exclusive package ditemukan.\n";
    exit(0);
}

// Update semua order exclusive yang remaining_classes > 0
$orders = Order::whereIn('package_id', $exclusivePackageIds)
    ->where('remaining_classes', '>', 0)
    ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
    ->get();

echo "Orders to update: " . $orders->count() . "\n\n";

foreach ($orders as $order) {
    $old = $order->remaining_classes;
    $order->update(['remaining_classes' => 0]);
    echo "Order #{$order->id} ({$order->order_code}): remaining_classes {$old} -> 0\n";
}

echo "\nDone! Updated " . $orders->count() . " orders.\n";
