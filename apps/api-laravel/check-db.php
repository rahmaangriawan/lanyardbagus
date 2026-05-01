<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('users');
echo "Users table columns:\n";
print_r($columns);

// Check if users exist
$users = \App\Models\User::all();
echo "\nUsers count: " . $users->count() . "\n";
foreach ($users as $u) {
    echo "- {$u->email} (role_id: {$u->role_id})\n";
}
