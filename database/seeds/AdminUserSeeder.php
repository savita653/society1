<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating User.
        $user = \App\User::create([
            'name' => 'Super Administrator',
            'email' => 'sadmin@rl.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make('password'),
        ]);
        
        // Assigning admin role to User.
        $user->syncRoles(['super_admin']);

        // Creating User.
        // $user = \App\User::create([
        //     'name' => 'Presenter',
        //     'email' => 'presenter@rl.com',
        //     'email_verified_at' => date('Y-m-d H:i:s'),
        //     'password' => Hash::make('password'),
        // ]);
        
        // Assigning admin role to User.
        // $user->syncRoles(['presenter']);

        // Creating User.
        // $user = \App\User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@rl.com',
        //     'email_verified_at' => date('Y-m-d H:i:s'),
        //     'password' => Hash::make('password'),
        // ]);
        
        // Assigning admin role to User.
        // $user->syncRoles(['admin']);

        // Creating User.
        // $user = \App\User::create([
        //     'name' => 'Subscriber',
        //     'email' => 'user@rl.com',
        //     'email_verified_at' => date('Y-m-d H:i:s'),
        //     'password' => Hash::make('password'),
        // ]);
        
        // Assigning admin role to User.
        // $user->syncRoles(['subscriber']);

        // $this->command->info('Created Admin User:Admin Credential:Email: admin@rl.com Password: password');
    }
}
