<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // Insert default permissions
        DB::table('permissions')->insert([
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
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
}; 