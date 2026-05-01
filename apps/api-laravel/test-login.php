<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

$email = 'admin@example.com';
$password = 'password';

// 1. Cek user exists
$user = User::where('email', $email)->first();
echo "1. User lookup:\n";
if ($user) {
    echo "   User FOUND: ID={$user->id}, Email={$user->email}\n";
    echo "   Password hash: " . substr($user->password, 0, 30) . "...\n";
    
    // 2. Test password verify
    $verify = password_verify($password, $user->password);
    echo "2. password_verify(): " . ($verify ? 'TRUE' : 'FALSE') . "\n";
    
    // 3. Test Hash::check
    $hashCheck = Hash::check($password, $user->password);
    echo "3. Hash::check(): " . ($hashCheck ? 'TRUE' : 'FALSE') . "\n";
    
    // 4. Test Auth::attempt
    $attempt = Auth::attempt(['email' => $email, 'password' => $password]);
    echo "4. Auth::attempt(): " . ($attempt ? 'TRUE' : 'FALSE') . "\n";
    
    // 5. Reset password if all fail
    if (!$verify && !$hashCheck && !$attempt) {
        echo "\n5. RESETTING password...\n";
        $user->password = Hash::make($password);
        $user->save();
        echo "   Password reset to: {$password}\n";
        
        // Verify again
        $user->refresh();
        $verify2 = password_verify($password, $user->password);
        echo "   After reset - password_verify(): " . ($verify2 ? 'TRUE' : 'FALSE') . "\n";
    }
} else {
    echo "   User NOT FOUND!\n";
    echo "   Creating user...\n";
    $newUser = User::create([
        'name' => 'Admin',
        'email' => $email,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ]);
    echo "   Created user ID: {$newUser->id}\n";
}

echo "\n========================================\n";
echo "Test login dengan: {$email} / {$password}\n";
echo "========================================\n";
