<?php
// Fix session config untuk development - complete fix
$envPath = __DIR__ . '/.env';
$env = file_get_contents($envPath);

// Update SESSION_DRIVER ke database
$env = preg_replace('/SESSION_DRIVER=.*/', 'SESSION_DRIVER=database', $env);

// Update SESSION_DOMAIN ke null (agar mengikuti request domain)
$env = preg_replace('/SESSION_DOMAIN=.*/', 'SESSION_DOMAIN=null', $env);

// Update SESSION_SAME_SITE ke none untuk development
$env = preg_replace('/SESSION_SAME_SITE=.*/', 'SESSION_SAME_SITE=none', $env);

// Update SESSION_SECURE false untuk http
$env = preg_replace('/SESSION_SECURE=.*/', 'SESSION_SECURE=false', $env);

// Update SESSION_HTTP_ONLY true
$env = preg_replace('/SESSION_HTTP_ONLY=.*/', 'SESSION_HTTP_ONLY=true', $env);

// Update SESSION_PATH
$env = preg_replace('/SESSION_PATH=.*/', 'SESSION_PATH=/', $env);

file_put_contents($envPath, $env);

echo "✓ Session config fixed!\n";
echo "SESSION_DRIVER=database\n";
echo "SESSION_DOMAIN=null\n";
echo "SESSION_SAME_SITE=none\n";
echo "SESSION_SECURE=false\n";
echo "SESSION_HTTP_ONLY=true\n";
echo "SESSION_PATH=/\n";
echo "\nSilakan restart Laravel server!\n";
