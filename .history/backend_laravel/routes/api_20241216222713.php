<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Test route - remove this after testing
    Route::post('/test-db', function (Request $request) {
        try {
            $user = \App\Models\User::create([
                'name' => 'Test User',
                'email' => 'test'.time().'@example.com',
                'password' => bcrypt('password123'),
                'role_id' => 4
            ]);
            
            return response()->json([
                'message' => 'Test successful',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('departments', DepartmentController::class);
        
        // Attendance routes
        Route::prefix('attendances')->group(function () {
            Route::post('clock-in', [AttendanceController::class, 'clockIn']);
            Route::post('clock-out', [AttendanceController::class, 'clockOut']);
            Route::get('summary', [AttendanceController::class, 'summary']);
            Route::get('employee-report', [AttendanceController::class, 'employeeReport']);
            Route::get('department-report', [AttendanceController::class, 'departmentReport']);
        });
        Route::apiResource('attendances', AttendanceController::class);
        
        // Payroll routes
        Route::prefix('payrolls')->group(function () {
            Route::post('generate', [PayrollController::class, 'generate']);
            Route::post('bulk-approve', [PayrollController::class, 'bulkApprove']);
            Route::post('process-batch', [PayrollController::class, 'processBatch']);
            Route::get('statistics', [PayrollController::class, 'statistics']);
            Route::get('department-report', [PayrollController::class, 'departmentReport']);
            Route::get('{payroll}/payslip', [PayrollController::class, 'generatePayslip']);
            Route::get('forecast', [PayrollController::class, 'forecast']);
            Route::get('analyze', [PayrollController::class, 'analyze']);
        });
        Route::apiResource('payrolls', PayrollController::class);
    });
}); 