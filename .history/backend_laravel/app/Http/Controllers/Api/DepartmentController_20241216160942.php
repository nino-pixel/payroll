<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $query = Department::with(['manager', 'employees'])
            ->withCount(['employees', 'employees as active_employee_count' => function ($query) {
                $query->where('status', 'active');
            }]);

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply pagination
        $perPage = $request->input('per_page', 10);
        $departments = $query->paginate($perPage);

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

    public function show(Request $request, Department $department): JsonResponse
    {
        $department->load(['manager', 'employees' => function ($query) {
            $query->with('user')->where('status', 'active');
        }]);

        // Calculate department statistics
        $stats = [
            'total_employees' => $department->employees_count,
            'active_employees' => $department->employees()->where('status', 'active')->count(),
            'total_payroll' => $department->employees()->where('status', 'active')->sum('salary'),
            'average_salary' => $department->employees()->where('status', 'active')->avg('salary') ?? 0
        ];

        return $this->successResponse([
            'id' => $department->id,
            'name' => $department->name,
            'code' => $department->code,
            'description' => $department->description,
            'manager' => $department->manager,
            'employees' => $department->employees,
            'stats' => $stats
        ]);
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