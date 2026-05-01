<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin role if not exists
        $adminRole = Role::firstOrCreate(
            ['name' => 'ADMIN'],
            ['description' => 'Administrator']
        );
        
        // Create editor role if not exists  
        $editorRole = Role::firstOrCreate(
            ['name' => 'EDITOR'],
            ['description' => 'Editor']
        );
        
        // Update existing admin@local.test
        $user1 = User::where('email', 'admin@local.test')->first();
        if ($user1) {
            $user1->password = Hash::make('password');
            $user1->role_id = $adminRole->id;
            $user1->save();
        }
        
        // Create/Update admin@example.com
        $user2 = User::where('email', 'admin@example.com')->first();
        if ($user2) {
            $user2->password = Hash::make('password');
            $user2->role_id = $adminRole->id;
            $user2->save();
        } else {
            User::create([
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]);
        }
        
        // Create/Update admin@lanyardbagus.com
        $user3 = User::where('email', 'admin@lanyardbagus.com')->first();
        if ($user3) {
            $user3->password = Hash::make('admin123');
            $user3->role_id = $adminRole->id;
            $user3->save();
        } else {
            User::create([
                'email' => 'admin@lanyardbagus.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
            ]);
        }
        
        $this->command->info('=================================');
        $this->command->info('Users updated/created:');
        $this->command->info('1. admin@local.test / password');
        $this->command->info('2. admin@example.com / password');
        $this->command->info('3. admin@lanyardbagus.com / admin123');
        $this->command->info('Role: ADMIN');
        $this->command->info('=================================');
    }
}
