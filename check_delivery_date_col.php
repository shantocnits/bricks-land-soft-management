<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Challans has delivery_date? " . (Illuminate\Support\Facades\Schema::hasColumn('challans', 'delivery_date') ? 'YES' : 'NO') . "\n";
echo "Deliveries has delivery_date? " . (Illuminate\Support\Facades\Schema::hasColumn('deliveries', 'delivery_date') ? 'YES' : 'NO') . "\n";
