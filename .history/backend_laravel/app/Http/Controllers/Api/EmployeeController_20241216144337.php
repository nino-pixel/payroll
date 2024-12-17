<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends ApiController
{
    public function index(): JsonResponse
    {
        $employees = Employee::with(['department', 'user'])->paginate(10);
        return $this->successResponse($employees);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'employee_code' => 'required|unique:employees',
            'position' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,on_leave'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $employee = Employee::create($validator->validated());
        return $this->successResponse($employee, 'Employee created successfully', 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        $employee->load(['department', 'user', 'attendances', 'payrolls']);
        return $this->successResponse($employee);
    }

    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'exists:departments,id',
            'position' => 'string',
            'salary' => 'numeric|min:0',
            'status' => 'in:active,inactive,on_leave'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $employee->update($validator->validated());
        return $this->successResponse($employee, 'Employee updated successfully');
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();
        return $this->successResponse(null, 'Employee deleted successfully');
    }
} 