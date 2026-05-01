<?php
// Fix session config untuk development
$envPath = __DIR__ . '/.env';
$env = file_get_contents($envPath);

// Update SESSION_DOMAIN ke null (agar mengikuti request domain)
$env = preg_replace('/SESSION_DOMAIN=.*/', 'SESSION_DOMAIN=null', $env);

// Update SESSION_SAME_SITE ke none untuk development (atau lax dengan secure)
$env = preg_replace('/SESSION_SAME_SITE=.*/', 'SESSION_SAME_SITE=none', $env);

// Pastikan SESSION_SECURE false untuk http development
$env = preg_replace('/SESSION_SECURE=.*/', 'SESSION_SECURE=false', $env);

file_put_contents($envPath, $env);

echo "✓ Session config fixed!\n";
echo "SESSION_DOMAIN=null\n";
echo "SESSION_SAME_SITE=none\n";
echo "SESSION_SECURE=false\n";
echo "\nSilakan restart Laravel server!\n";
