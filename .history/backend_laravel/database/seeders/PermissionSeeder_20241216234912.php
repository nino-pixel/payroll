<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view'],
            ['name' => 'Create Users', 'slug' => 'users.create'],
            ['name' => 'Edit Users', 'slug' => 'users.edit'],
            ['name' => 'Delete Users', 'slug' => 'users.delete'],
            
            // Department Management
            ['name' => 'View Departments', 'slug' => 'departments.view'],
            ['name' => 'Create Departments', 'slug' => 'departments.create'],
            ['name' => 'Edit Departments', 'slug' => 'departments.edit'],
            ['name' => 'Delete Departments', 'slug' => 'departments.delete'],
            
            // Payroll Management
            ['name' => 'View Payroll', 'slug' => 'payroll.view'],
            ['name' => 'Process Payroll', 'slug' => 'payroll.process'],
            ['name' => 'Approve Payroll', 'slug' => 'payroll.approve']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 