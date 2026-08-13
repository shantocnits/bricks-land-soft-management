<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- SEARCHING CHALLANS FOR shanto11 ---\n";
$challans = App\Models\Challan::where('customer_name', 'like', '%shanto11%')
    ->orWhere('customer_phone', 'like', '%shanto11%')
    ->get();

echo "Count: " . $challans->count() . "\n";
foreach ($challans as $c) {
    echo "ID: {$c->id} | No: {$c->challan_no} | Name: {$c->customer_name} | Date: {$c->date} | Type: {$c->challan_type} | Season: '{$c->season}' | Created: {$c->created_at}\n";
}

echo "\n--- ACTIVE SEASON SETTING ---\n";
echo "Active Season: " . App\Models\Setting::get('season', '২৫-২৬') . "\n";
