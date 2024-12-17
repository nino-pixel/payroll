<?php

namespace App\Http\Controllers\Api;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
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
                // Calculate basic pay
                $workingDays = now()->parse($request->pay_period_start)
                    ->diffInWeekdays(now()->parse($request->pay_period_end));
                
                $basicPay = ($employee->salary / 22) * $workingDays;
                
                // Calculate overtime pay
                $overtimePay = $this->calculateOvertimePay(
                    $employee, 
                    $request->pay_period_start, 
                    $request->pay_period_end
                );
                
                // Calculate gross pay
                $grossPay = $basicPay + $overtimePay;
                
                // Calculate deductions
                $deductions = $this->calculateDeductions($employee, $grossPay);
                
                // Calculate net pay
                $netPay = $grossPay - $deductions;

                $payrolls[] = Payroll::create([
                    'employee_id' => $employee->id,
                    'pay_period_start' => $request->pay_period_start,
                    'pay_period_end' => $request->pay_period_end,
                    'basic_pay' => $basicPay,
                    'overtime_pay' => $overtimePay,
                    'deductions' => $deductions,
                    'net_pay' => $netPay,
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

    /**
     * Calculate overtime pay based on attendance records
     */
    private function calculateOvertimePay(Employee $employee, string $startDate, string $endDate): float
    {
        $overtimeHours = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('time_out')
            ->get()
            ->sum(function ($attendance) {
                $timeIn = now()->parse($attendance->time_in);
                $timeOut = now()->parse($attendance->time_out);
                $hoursWorked = $timeOut->diffInHours($timeIn);
                return max(0, $hoursWorked - 8); // Assuming 8 hours is regular time
            });

        // Assuming overtime rate is 1.25 times the regular hourly rate
        $hourlyRate = $employee->salary / (22 * 8); // 22 working days, 8 hours per day
        return $overtimeHours * ($hourlyRate * 1.25);
    }

    /**
     * Calculate deductions (tax, benefits, etc.)
     */
    private function calculateDeductions(Employee $employee, float $grossPay): float
    {
        // Simplified tax calculation (this should be more complex in real world)
        $taxRate = 0.1; // 10% tax
        $tax = $grossPay * $taxRate;

        // Add other deductions like health insurance, social security, etc.
        $otherDeductions = 0;

        return $tax + $otherDeductions;
    }

    /**
     * Bulk approve payrolls
     */
    public function bulkApprove(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $updated = Payroll::whereIn('id', $request->payroll_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'processed',
                'updated_at' => now()
            ]);

        return $this->successResponse([
            'processed_count' => $updated
        ], 'Payrolls processed successfully');
    }

    /**
     * Generate payslips for approved payrolls
     */
    public function generatePayslip(Payroll $payroll): JsonResponse
    {
        if ($payroll->status !== 'processed') {
            return $this->errorResponse('Payroll must be processed before generating payslip', 422);
        }

        // Here you would generate a PDF payslip
        // This is a simplified example returning payslip data
        $payslipData = [
            'employee' => $payroll->employee->load('department', 'user'),
            'payroll_period' => [
                'start' => $payroll->pay_period_start,
                'end' => $payroll->pay_period_end
            ],
            'earnings' => [
                'basic_pay' => $payroll->basic_pay,
                'overtime_pay' => $payroll->overtime_pay,
                'total_earnings' => $payroll->basic_pay + $payroll->overtime_pay
            ],
            'deductions' => [
                'tax' => $payroll->deductions * 0.7, // Assuming 70% of deductions is tax
                'other_deductions' => $payroll->deductions * 0.3,
                'total_deductions' => $payroll->deductions
            ],
            'net_pay' => $payroll->net_pay
        ];

        return $this->successResponse($payslipData);
    }
} 