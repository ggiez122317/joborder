<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$others = \DB::table('other_information')->get();
echo "Other Information records count: " . $others->count() . "\n";
foreach ($others as $other) {
    echo "Employee ID {$other->employee_id}:\n";
    echo "  Visibility: " . $other->visibility . "\n";
}
