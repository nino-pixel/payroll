<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends ApiController
{
    public function summary(): JsonResponse
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        // Get today's attendance
        $todayAttendance = null;
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', today())
                ->first();
        }

        // Get latest payroll
        $latestPayroll = null;
        if ($employee) {
            $latestPayroll = Payroll::where('employee_id', $employee->id)
                ->latest()
                ->first();
        }

        return $this->successResponse([
            'user_name' => $user->name,
            'department' => $employee?->department?->name ?? 'N/A',
            'attendance_status' => $todayAttendance?->status ?? 'Not Clocked In',
            'clock_in_time' => $todayAttendance?->time_in?->format('H:i'),
            'latest_payroll' => $latestPayroll?->net_pay ?? 0,
            'pay_period' => $latestPayroll ? 
                $latestPayroll->pay_period_start->format('M d') . ' - ' . 
                $latestPayroll->pay_period_end->format('M d, Y') : 'N/A',
        ]);
    }
} 