<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Employee::with(['department', 'user']);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortDesc = $request->boolean('sort_desc', false);
        
        if (in_array($sortBy, ['name', 'email', 'status', 'salary'])) {
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }

        // Apply pagination
        $perPage = $request->input('per_page', 10);
        $employees = $query->paginate($perPage);

        return $this->successResponse($employees);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|in:active,inactive',
            'salary' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $employee = Employee::create($validator->validated());
        return $this->successResponse($employee, 'Employee created successfully', 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        return $this->successResponse($employee->load(['department', 'user']));
    }

    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $employee->user_id,
            'department_id' => 'exists:departments,id',
            'status' => 'in:active,inactive',
            'salary' => 'numeric|min:0'
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