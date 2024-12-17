<?php

namespace App\Http\Controllers\Api;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Payroll::with('employee.user');
        
        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('pay_period_start', [$request->start_date, $request->end_date]);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->latest('pay_period_start')->paginate(10);
        return $this->successResponse($payrolls);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start',
            'basic_pay' => 'required|numeric|min:0',
            'overtime_pay' => 'numeric|min:0',
            'deductions' => 'numeric|min:0',
            'net_pay' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processed,paid'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // Check for duplicate payroll period
        $exists = Payroll::where('employee_id', $request->employee_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('pay_period_start', [$request->pay_period_start, $request->pay_period_end])
                    ->orWhereBetween('pay_period_end', [$request->pay_period_start, $request->pay_period_end]);
            })
            ->exists();

        if ($exists) {
            return $this->errorResponse('Payroll already exists for this period', 422);
        }

        $payroll = Payroll::create($validator->validated());
        return $this->successResponse($payroll, 'Payroll created successfully', 201);
    }

    public function show(Payroll $payroll): JsonResponse
    {
        $payroll->load('employee.user');
        return $this->successResponse($payroll);
    }

    public function update(Request $request, Payroll $payroll): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'basic_pay' => 'numeric|min:0',
            'overtime_pay' => 'numeric|min:0',
            'deductions' => 'numeric|min:0',
            'net_pay' => 'numeric|min:0',
            'status' => 'in:pending,processed,paid'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $payroll->update($validator->validated());
        return $this->successResponse($payroll, 'Payroll updated successfully');
    }

    public function destroy(Payroll $payroll): JsonResponse
    {
        $payroll->delete();
        return $this->successResponse(null, 'Payroll deleted successfully');
    }

    /**
     * Generate payroll for a specific period
     */
    public function generate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'nullable|exists:departments,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // Get employees query
        $employeesQuery = Employee::where('status', 'active');
        
        if ($request->has('department_id')) {
            $employeesQuery->where('department_id', $request->department_id);
        }

        $employees = $employeesQuery->get();
        $payrolls = [];

        foreach ($employees as $employee) {
            // Check if payroll already exists
            $exists = Payroll::where('employee_id', $employee->id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('pay_period_start', [
                        $request->pay_period_start, 
                        $request->pay_period_end
                    ])
                    ->orWhereBetween('pay_period_end', [
                        $request->pay_period_start, 
                        $request->pay_period_end
                    ]);
                })
                ->exists();

            if (!$exists) {
                // Calculate basic pay (simplified version)
                $workingDays = now()->parse($request->pay_period_start)
                    ->diffInWeekdays(now()->parse($request->pay_period_end));
                
                $basicPay = ($employee->salary / 22) * $workingDays;

                $payrolls[] = Payroll::create([
                    'employee_id' => $employee->id,
                    'pay_period_start' => $request->pay_period_start,
                    'pay_period_end' => $request->pay_period_end,
                    'basic_pay' => $basicPay,
                    'overtime_pay' => 0, // This should be calculated based on attendance
                    'deductions' => 0, // This should include tax and other deductions
                    'net_pay' => $basicPay, // This should be basic_pay + overtime - deductions
                    'status' => 'pending'
                ]);
            }
        }

        return $this->successResponse([
            'payrolls_generated' => count($payrolls),
            'payrolls' => $payrolls
        ], 'Payroll generated successfully');
    }

    /**
     * Get payroll summary statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:2099',
            'month' => 'nullable|integer|min:1|max:12'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $query = Payroll::whereYear('pay_period_start', $request->year);
        
        if ($request->has('month')) {
            $query->whereMonth('pay_period_start', $request->month);
        }

        $statistics = [
            'total_payroll' => $query->sum('net_pay'),
            'total_basic_pay' => $query->sum('basic_pay'),
            'total_overtime_pay' => $query->sum('overtime_pay'),
            'total_deductions' => $query->sum('deductions'),
            'payroll_count' => $query->count(),
            'status_summary' => $query->groupBy('status')
                ->selectRaw('status, COUNT(*) as count, SUM(net_pay) as total')
                ->get()
        ];

        return $this->successResponse($statistics);
    }
} 