<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('email', 'admin@lanyardbagus.com')->first();

if ($u) {
    echo "User found: " . $u->email . "\n";
    echo "Name: " . $u->name . "\n";
    $check = password_verify('admin123', $u->password);
    echo "Password check: " . ($check ? 'OK' : 'FAIL') . "\n";
    
    if (!$check) {
        // Reset password
        $u->password = bcrypt('admin123');
        $u->save();
        echo "Password RESET to: admin123\n";
    }
} else {
    echo "User NOT found, creating...\n";
    User::create([
        'name' => 'Admin',
        'email' => 'admin@lanyardbagus.com',
        'password' => bcrypt('admin123'),
        'email_verified_at' => now(),
    ]);
    echo "Created: admin@lanyardbagus.com / admin123\n";
}
