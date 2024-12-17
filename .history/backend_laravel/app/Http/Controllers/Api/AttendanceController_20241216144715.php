<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with('employee.user');
        
        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->latest('date')->paginate(10);
        return $this->successResponse($attendances);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:Y-m-d H:i:s',
            'time_out' => 'nullable|date_format:Y-m-d H:i:s|after:time_in',
            'status' => 'required|in:present,absent,late,half_day',
            'location_in' => 'nullable|array',
            'location_out' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // Check for duplicate attendance
        $exists = Attendance::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->exists();

        if ($exists) {
            return $this->errorResponse('Attendance already exists for this date', 422);
        }

        $attendance = Attendance::create($validator->validated());
        return $this->successResponse($attendance, 'Attendance recorded successfully', 201);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        $attendance->load('employee.user');
        return $this->successResponse($attendance);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'time_out' => 'nullable|date_format:Y-m-d H:i:s|after:time_in',
            'status' => 'in:present,absent,late,half_day',
            'location_out' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $attendance->update($validator->validated());
        return $this->successResponse($attendance, 'Attendance updated successfully');
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();
        return $this->successResponse(null, 'Attendance deleted successfully');
    }

    /**
     * Clock in for an employee
     */
    public function clockIn(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'location_in' => 'nullable|array',
            'location_in.latitude' => 'required_with:location_in|numeric',
            'location_in.longitude' => 'required_with:location_in|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // Check if already clocked in today
        $exists = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', now()->toDateString())
            ->exists();

        if ($exists) {
            return $this->errorResponse('Already clocked in today', 422);
        }

        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => now()->toDateString(),
            'time_in' => now(),
            'status' => 'present',
            'location_in' => $request->location_in
        ]);

        return $this->successResponse($attendance, 'Clocked in successfully', 201);
    }

    /**
     * Clock out for an employee
     */
    public function clockOut(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'location_out' => 'nullable|array',
            'location_out.latitude' => 'required_with:location_out|numeric',
            'location_out.longitude' => 'required_with:location_out|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', now()->toDateString())
            ->whereNull('time_out')
            ->first();

        if (!$attendance) {
            return $this->errorResponse('No active clock-in found', 404);
        }

        $attendance->update([
            'time_out' => now(),
            'location_out' => $request->location_out
        ]);

        return $this->successResponse($attendance, 'Clocked out successfully');
    }

    /**
     * Get attendance summary for a date range
     */
    public function summary(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $query = Attendance::whereBetween('date', [
                $request->start_date, 
                $request->end_date
            ])
            ->selectRaw('
                status,
                COUNT(*) as count,
                DATE(date) as attendance_date
            ')
            ->groupBy('status', 'attendance_date');

        if ($request->has('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $summary = $query->get();

        return $this->successResponse([
            'summary' => $summary,
            'total_days' => $summary->pluck('attendance_date')->unique()->count(),
            'statistics' => [
                'present' => $summary->where('status', 'present')->sum('count'),
                'absent' => $summary->where('status', 'absent')->sum('count'),
                'late' => $summary->where('status', 'late')->sum('count'),
                'half_day' => $summary->where('status', 'half_day')->sum('count')
            ]
        ]);
    }
} 