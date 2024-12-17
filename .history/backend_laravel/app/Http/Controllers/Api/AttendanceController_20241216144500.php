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
} 