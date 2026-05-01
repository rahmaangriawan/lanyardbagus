<?php
$adminRole = App\Models\Role::where('name', 'ADMIN')->first();
if (!$adminRole) {
    echo "ADMIN role NOT FOUND in database!\n";
    $roles = App\Models\Role::all()->pluck('name')->toArray();
    echo "Available roles: " . implode(', ', $roles) . "\n";
} else {
    echo "Admin Role ID is: " . $adminRole->id . "\n";
    App\Models\User::query()->update(['role_id' => $adminRole->id]);
    $users = App\Models\User::all();
    echo "Total users updated: " . $users->count() . "\n";
    foreach ($users as $user) {
        echo "User {$user->email} has role_id: {$user->role_id}\n";
    }
}
