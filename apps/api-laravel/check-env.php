<?php
// Script untuk cek environment dan database
require __DIR__ . '/vendor/autoload.php';

$envPath = __DIR__ . '/.env';
echo "=== Environment Check ===\n\n";

if (file_exists($envPath)) {
    echo "✓ .env file exists\n";
    $env = file_get_contents($envPath);
    
    // Extract DB settings
    preg_match('/DB_CONNECTION=(.*)/', $env, $dbConn);
    preg_match('/DB_HOST=(.*)/', $env, $dbHost);
    preg_match('/DB_DATABASE=(.*)/', $env, $dbName);
    preg_match('/DB_USERNAME=(.*)/', $env, $dbUser);
    
    echo "DB_CONNECTION: " . ($dbConn[1] ?? 'NOT SET') . "\n";
    echo "DB_HOST: " . ($dbHost[1] ?? 'NOT SET') . "\n";
    echo "DB_DATABASE: " . ($dbName[1] ?? 'NOT SET') . "\n";
    echo "DB_USERNAME: " . ($dbUser[1] ?? 'NOT SET') . "\n";
    
    // Check session driver
    preg_match('/SESSION_DRIVER=(.*)/', $env, $session);
    echo "SESSION_DRIVER: " . ($session[1] ?? 'NOT SET') . "\n";
} else {
    echo "✗ .env file NOT found\n";
}

echo "\n=== Database Connection Test ===\n";

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $connection = config('database.default');
    echo "Default Connection: $connection\n";
    
    if ($connection === 'mysql') {
        \DB::select('SELECT 1');
        echo "✓ MySQL connected successfully\n";
    } else if ($connection === 'sqlite') {
        echo "⚠ Using SQLite (not MySQL)\n";
    }
    
    // Check sessions table
    try {
        $sessions = \DB::select('SELECT COUNT(*) as count FROM sessions');
        echo "✓ Sessions table exists\n";
    } catch (Exception $e) {
        echo "✗ Sessions table missing: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== Recommendations ===\n";
echo "Untuk menggunakan MySQL dari Laragon, update .env file:\n";
echo "DB_CONNECTION=mysql\n";
echo "DB_HOST=127.0.0.1\n";
echo "DB_PORT=3306\n";
echo "DB_DATABASE=lanyardbagus\n";
echo "DB_USERNAME=root\n";
echo "DB_PASSWORD=\n";
echo "SESSION_DRIVER=database\n";
