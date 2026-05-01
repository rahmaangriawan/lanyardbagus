<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = DB::table('sessions')->count();
echo "Sessions count: " . $count . "\n";

// Show recent sessions
$sessions = DB::table('sessions')->orderBy('last_activity', 'desc')->limit(3)->get();
foreach ($sessions as $s) {
    echo "Session ID: " . substr($s->id, 0, 20) . "...\n";
    echo "  IP: " . $s->ip_address . "\n";
    echo "  Last activity: " . $s->last_activity . "\n";
}
