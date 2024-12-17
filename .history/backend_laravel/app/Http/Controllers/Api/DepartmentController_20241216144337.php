<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends ApiController
{
    public function index(): JsonResponse
    {
        $departments = Department::withCount('employees')->paginate(10);
        return $this->successResponse($departments);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $department = Department::create($validator->validated());
        return $this->successResponse($department, 'Department created successfully', 201);
    }

    public function show(Department $department): JsonResponse
    {
        $department->load('employees');
        return $this->successResponse($department);
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $department->update($validator->validated());
        return $this->successResponse($department, 'Department updated successfully');
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();
        return $this->successResponse(null, 'Department deleted successfully');
    }
} 