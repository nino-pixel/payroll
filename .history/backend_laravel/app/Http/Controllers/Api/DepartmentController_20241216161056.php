<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DepartmentController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string|max:255',
            'sort_by' => 'nullable|string|in:name,code,employees_count',
            'sort_desc' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $query = Department::with(['manager', 'employees'])
            ->withCount(['employees', 'employees as active_employee_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->withSum(['employees as total_payroll' => function ($query) {
                $query->where('status', 'active');
            }], 'salary');

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortDesc = $request->boolean('sort_desc', false);
        $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        // Apply pagination
        $perPage = $request->input('per_page', 10);
        $departments = $query->paginate($perPage);

        return $this->successResponse($departments);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            $department = Department::create($validator->validated());
            DB::commit();
            return $this->successResponse($department->load('manager'), 'Department created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create department', 500);
        }
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
            'average_salary' => $department->employees()->where('status', 'active')->avg('salary') ?? 0,
            'payroll_trend' => $this->getPayrollTrend($department)
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
            'name' => 'string|max:255|unique:departments,name,' . $department->id,
            'code' => 'string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            $department->update($validator->validated());
            DB::commit();
            return $this->successResponse($department->load('manager'), 'Department updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update department', 500);
        }
    }

    public function destroy(Department $department): JsonResponse
    {
        if ($department->employees()->exists()) {
            return $this->errorResponse('Cannot delete department with active employees', 422);
        }

        DB::beginTransaction();
        try {
            $department->delete();
            DB::commit();
            return $this->successResponse(null, 'Department deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete department', 500);
        }
    }

    private function getPayrollTrend(Department $department): array
    {
        return DB::table('payrolls')
            ->join('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->where('employees.department_id', $department->id)
            ->where('payrolls.created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(payrolls.created_at, "%Y-%m") as month'),
                DB::raw('SUM(payrolls.net_pay) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }
} 