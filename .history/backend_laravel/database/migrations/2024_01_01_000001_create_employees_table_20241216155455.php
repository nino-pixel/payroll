<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('department_id')->constrained()->onDelete('restrict');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('salary', 10, 2);
            $table->date('hire_date')->nullable();
            $table->string('position')->nullable();
            $table->string('employee_id')->unique()->nullable();
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}; 