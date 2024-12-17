<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

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