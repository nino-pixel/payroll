<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create default roles
        DB::table('roles')->insert([
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access'
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Department management and employee oversight'
            ],
            [
                'name' => 'HR Staff',
                'slug' => 'hr',
                'description' => 'Employee and payroll management'
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Basic access to personal information'
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}; 