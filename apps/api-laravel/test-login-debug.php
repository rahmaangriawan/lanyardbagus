<?php
// Debug login issue
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== LOGIN DEBUG ===\n\n";

// Check user exists
$email = 'admin@example.com';
$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "✗ User not found: {$email}\n";
    
    // List all users
    echo "\nAvailable users:\n";
    $users = \App\Models\User::all();
    foreach ($users as $u) {
        echo "- {$u->email} (ID: {$u->id})\n";
    }
    exit;
}

echo "✓ User found: {$email}\n";
echo "  ID: {$user->id}\n";
echo "  Name: " . ($user->name ?? 'N/A') . "\n";
echo "  Role ID: " . ($user->role_id ?? 'N/A') . "\n";

// Check password
$testPassword = 'password';
$hashCheck = password_verify($testPassword, $user->password);
echo "\n✓ Password '{$testPassword}' verify: " . ($hashCheck ? 'SUCCESS' : 'FAILED') . "\n";

// Check if session table exists
try {
    \DB::select('SELECT COUNT(*) FROM sessions');
    echo "\n✓ Sessions table exists\n";
} catch (Exception $e) {
    echo "\n✗ Sessions table missing: " . $e->getMessage() . "\n";
}

// Check sanctum config
echo "\n=== SANCTUM CONFIG ===\n";
echo "Stateful domains: " . implode(', ', config('sanctum.stateful')) . "\n";
$guard = config('sanctum.guard');
echo "Guard: " . (is_array($guard) ? implode(', ', $guard) : $guard) . "\n";

// Check session driver
echo "\nSession driver: " . config('session.driver') . "\n";
echo "Session domain: " . config('session.domain') . "\n";

// Test actual login attempt
echo "\n=== TEST LOGIN ===\n";
$guard = Auth::guard('web');
$attempt = $guard->attempt([
    'email' => $email,
    'password' => $testPassword
]);

echo "Auth attempt result: " . ($attempt ? 'SUCCESS' : 'FAILED') . "\n";

if ($attempt) {
    echo "✓ Login would succeed with these credentials\n";
} else {
    echo "✗ Login failed - check password hash\n";
    
    // Rehash password
    echo "\nRehashing password...\n";
    $user->password = bcrypt($testPassword);
    $user->save();
    echo "✓ Password rehashed. Try login again.\n";
}
