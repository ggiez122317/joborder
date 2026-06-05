<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'Front: ' . \App\Models\IdTemplate::getActiveImageUrl() . "\n";
echo 'Back: ' . \App\Models\IdTemplate::getActiveBackImageUrl() . "\n";
