<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- ALL CHALLANS FOR shanto11 ---\n";
$challans = App\Models\Challan::where('customer_name', 'like', '%shanto11%')->get();
foreach ($challans as $c) {
    echo "ID: {$c->id} | No: {$c->challan_no} | Date: {$c->date} | DeliveryDate: '{$c->delivery_date}' | DueDate: '{$c->due_payment_date}'\n";
}

echo "\n--- CHECKING ALL DELIVERIES FOR shanto11 CHALLANS ---\n";
$deliveries = App\Models\Delivery::whereIn('challan_id', $challans->pluck('id'))->get();
foreach ($deliveries as $d) {
    echo "Delivery ID: {$d->id} | Challan ID: {$d->challan_id} | Delivery Date: {$d->delivery_date}\n";
}
