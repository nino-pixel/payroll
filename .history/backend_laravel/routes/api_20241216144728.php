<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PayrollController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    
    // Attendance routes
    Route::prefix('attendances')->group(function () {
        Route::post('clock-in', [AttendanceController::class, 'clockIn']);
        Route::post('clock-out', [AttendanceController::class, 'clockOut']);
        Route::get('summary', [AttendanceController::class, 'summary']);
    });
    Route::apiResource('attendances', AttendanceController::class);
    
    // Payroll routes
    Route::prefix('payrolls')->group(function () {
        Route::post('generate', [PayrollController::class, 'generate']);
        Route::get('statistics', [PayrollController::class, 'statistics']);
    });
    Route::apiResource('payrolls', PayrollController::class);
}); 