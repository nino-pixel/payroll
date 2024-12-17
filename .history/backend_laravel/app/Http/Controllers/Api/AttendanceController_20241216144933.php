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
            'time_in' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) use ($request) {
                    if (isset($request->date)) {
                        $timeInDate = now()->parse($value)->toDateString();
                        if ($timeInDate !== $request->date) {
                            $fail('The time in must be on the same date as the attendance date.');
                        }
                    }
                }
            ],
            'time_out' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
                'after:time_in',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && isset($request->date)) {
                        $timeOutDate = now()->parse($value)->toDateString();
                        if ($timeOutDate !== $request->date) {
                            $fail('The time out must be on the same date as the attendance date.');
                        }
                    }
                }
            ],
            'status' => 'required|in:present,absent,late,half_day',
            'location_in' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if ($value && (!isset($value['latitude']) || !isset($value['longitude']))) {
                        $fail('Location must include both latitude and longitude.');
                    }
                }
            ],
            'location_in.latitude' => 'required_with:location_in|numeric|between:-90,90',
            'location_in.longitude' => 'required_with:location_in|numeric|between:-180,180',
            'location_out' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if ($value && (!isset($value['latitude']) || !isset($value['longitude']))) {
                        $fail('Location must include both latitude and longitude.');
                    }
                }
            ],
            'location_out.latitude' => 'required_with:location_out|numeric|between:-90,90',
            'location_out.longitude' => 'required_with:location_out|numeric|between:-180,180'
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

    /**
     * Get employee attendance report
     */
    public function employeeReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $attendances = Attendance::where('employee_id', $request->employee_id)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->with('employee.user')
            ->get();

        $workHours = $attendances->sum(function ($attendance) {
            if (!$attendance->time_out) return 0;
            return now()->parse($attendance->time_out)->diffInHours(now()->parse($attendance->time_in));
        });

        $lateCount = $attendances->where('status', 'late')->count();
        $absentCount = $attendances->where('status', 'absent')->count();

        return $this->successResponse([
            'employee' => $attendances->first()?->employee,
            'period' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => now()->parse($request->start_date)->diffInDays($request->end_date) + 1
            ],
            'statistics' => [
                'total_work_hours' => $workHours,
                'average_hours_per_day' => $attendances->count() ? round($workHours / $attendances->count(), 2) : 0,
                'late_count' => $lateCount,
                'absent_count' => $absentCount,
                'attendance_rate' => $attendances->count() ? 
                    round((($attendances->count() - $absentCount) / $attendances->count()) * 100, 2) : 0
            ],
            'attendances' => $attendances
        ]);
    }

    /**
     * Get department attendance report
     */
    public function departmentReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $attendances = Attendance::whereHas('employee', function($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->with(['employee.user', 'employee.department'])
            ->get();

        $employeeStats = $attendances->groupBy('employee_id')
            ->map(function ($employeeAttendances) {
                $workHours = $employeeAttendances->sum(function ($attendance) {
                    if (!$attendance->time_out) return 0;
                    return now()->parse($attendance->time_out)->diffInHours(now()->parse($attendance->time_in));
                });

                return [
                    'employee' => $employeeAttendances->first()->employee,
                    'total_work_hours' => $workHours,
                    'late_count' => $employeeAttendances->where('status', 'late')->count(),
                    'absent_count' => $employeeAttendances->where('status', 'absent')->count(),
                    'attendance_rate' => round((($employeeAttendances->count() - $employeeAttendances->where('status', 'absent')->count()) 
                        / $employeeAttendances->count()) * 100, 2)
                ];
            });

        return $this->successResponse([
            'department' => $attendances->first()?->employee->department,
            'period' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ],
            'department_statistics' => [
                'total_employees' => $employeeStats->count(),
                'average_attendance_rate' => $employeeStats->avg('attendance_rate'),
                'total_late_count' => $employeeStats->sum('late_count'),
                'total_absent_count' => $employeeStats->sum('absent_count')
            ],
            'employee_statistics' => $employeeStats
        ]);
    }
} 