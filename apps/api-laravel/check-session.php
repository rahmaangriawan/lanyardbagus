<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "SESSION_DOMAIN: " . json_encode(config('session.domain')) . "\n";
echo "SESSION_COOKIE: " . config('session.cookie') . "\n";
echo "SESSION_PATH: " . config('session.path') . "\n";
echo "SESSION_SECURE: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "SESSION_HTTP_ONLY: " . (config('session.http_only') ? 'true' : 'false') . "\n";
echo "SESSION_SAME_SITE: " . config('session.same_site') . "\n";
echo "\nSANCTUM_STATEFUL: " . implode(', ', config('sanctum.stateful')) . "\n";
echo "SANCTUM_GUARD: " . json_encode(config('sanctum.guard')) . "\n";

// Check .env file for SESSION_DOMAIN
$env = file_get_contents(__DIR__ . '/.env');
preg_match('/SESSION_DOMAIN=(.*)/', $env, $match);
echo "\nENV SESSION_DOMAIN: " . ($match[1] ?? 'NOT SET') . "\n";
