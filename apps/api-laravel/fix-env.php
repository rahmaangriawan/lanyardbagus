<?php
// Script untuk fix environment variables
$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    echo "File .env tidak ditemukan!\n";
    exit(1);
}

$env = file_get_contents($envPath);

// Update SESSION_DRIVER to database for better CSRF handling
if (strpos($env, 'SESSION_DRIVER=database') === false) {
    $env = preg_replace('/SESSION_DRIVER=.*/', 'SESSION_DRIVER=database', $env);
    echo "✓ SESSION_DRIVER diubah ke 'database'\n";
}

// Update SANCTUM_STATEFUL_DOMAINS to include all dev ports
$statefulDomains = 'localhost:5173,localhost:5174,localhost:5175,127.0.0.1:5173,127.0.0.1:5174,127.0.0.1:5175,localhost:8000,127.0.0.1:8000';
if (strpos($env, 'SANCTUM_STATEFUL_DOMAINS') === false) {
    $env .= "\nSANCTUM_STATEFUL_DOMAINS={$statefulDomains}\n";
    echo "✓ SANCTUM_STATEFUL_DOMAINS ditambahkan\n";
} else {
    $env = preg_replace('/SANCTUM_STATEFUL_DOMAINS=.*/', "SANCTUM_STATEFUL_DOMAINS={$statefulDomains}", $env);
    echo "✓ SANCTUM_STATEFUL_DOMAINS diupdate\n";
}

// Ensure DB_CONNECTION is mysql
if (strpos($env, 'DB_CONNECTION=mysql') === false) {
    $env = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $env);
    echo "✓ DB_CONNECTION diubah ke 'mysql'\n";
}

file_put_contents($envPath, $env);
echo "\n✓ Environment file updated successfully!\n";
echo "Silakan restart Laravel server dengan: php artisan serve\n";
