<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$settings = Setting::all();
echo "Settings count: " . $settings->count() . "\n\n";

foreach ($settings as $s) {
    echo "Key: {$s->key}\n";
    echo "Group: {$s->group}\n";
    echo "Value: " . substr($s->value, 0, 50) . "...\n";
    echo "---\n";
}

echo "\nR2 Settings:\n";
$r2Settings = Setting::where('group', 'r2')->get();
foreach ($r2Settings as $s) {
    echo "- {$s->key}: " . ($s->value ? 'SET' : 'EMPTY') . "\n";
}
