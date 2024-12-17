<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run role and permission seeders first
        $this->call([
            PermissionSeeder::class,
            RolePermissionSeeder::class
        ]);

        // Create users after roles and permissions are set up
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1 // Admin role
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 4 // Employee role
        ]);
    }
}
