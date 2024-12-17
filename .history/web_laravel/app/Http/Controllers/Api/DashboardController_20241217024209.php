<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_employees' => Employee::count(),
                'active_employees' => Employee::where('status', 'active')->count(),
                'total_departments' => Department::count(),
                'recent_employees' => Employee::with('department')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($employee) {
                        return [
                            'id' => $employee->id,
                            'name' => $employee->name,
                            'position' => $employee->position,
                            'department' => $employee->department ? [
                                'id' => $employee->department->id,
                                'name' => $employee->department->name
                            ] : null
                        ];
                    })
            ];

            return $this->successResponse($stats, 'Dashboard statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving dashboard statistics: ' . $e->getMessage(), 500);
        }
    }
} 