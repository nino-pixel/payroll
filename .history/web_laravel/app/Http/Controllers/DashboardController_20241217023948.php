<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
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
                'recent_employees' => Employee::latest()->take(5)->get()
            ];

            return $this->successResponse($stats, 'Dashboard statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving dashboard statistics: ' . $e->getMessage(), 500);
        }
    }
} 