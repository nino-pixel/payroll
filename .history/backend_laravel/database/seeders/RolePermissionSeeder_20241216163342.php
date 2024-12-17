<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::all();
        $roles = Role::all();

        // Admin gets all permissions
        $adminRole = $roles->where('slug', 'admin')->first();
        $adminRole->permissions()->sync($permissions->pluck('id'));

        // Manager permissions
        $managerRole = $roles->where('slug', 'manager')->first();
        $managerRole->permissions()->sync(
            $permissions->whereIn('slug', [
                'departments.view',
                'departments.create',
                'departments.edit',
                'employees.view',
                'employees.create',
                'employees.edit',
                'payroll.view'
            ])->pluck('id')
        );

        // HR permissions
        $hrRole = $roles->where('slug', 'hr')->first();
        $hrRole->permissions()->sync(
            $permissions->whereIn('slug', [
                'employees.view',
                'employees.create',
                'employees.edit',
                'payroll.view',
                'payroll.process'
            ])->pluck('id')
        );

        // Employee permissions
        $employeeRole = $roles->where('slug', 'employee')->first();
        $employeeRole->permissions()->sync(
            $permissions->whereIn('slug', [
                'payroll.view'
            ])->pluck('id')
        );
    }
} 