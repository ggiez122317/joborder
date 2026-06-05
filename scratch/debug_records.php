<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\User;

$user = User::where('role', 'user')->first(); 
if (!$user) {
    echo "No user found\n";
    exit;
}

echo "Checking records for User ID: {$user->id} ({$user->name})\n";

$allCreated = Employee::where('created_by', $user->id)->get();
echo "Total records created by this user: " . $allCreated->count() . "\n";

foreach ($allCreated as $emp) {
    echo "- ID: {$emp->id}, Name: {$emp->full_name}, user_id: " . ($emp->user_id ?? 'NULL') . "\n";
}
