<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

foreach(User::all() as $u) {
    if(!$u->name) {
        $u->name = ucfirst($u->username);
        $u->save();
        echo "Updated user: " . $u->username . " to name: " . $u->name . "\n";
    }
}
