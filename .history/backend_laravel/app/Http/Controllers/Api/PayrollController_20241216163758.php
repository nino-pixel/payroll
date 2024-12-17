<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PayrollAnalytics;
use Illuminate\Support\Facades\DB;

class PayrollController extends ApiController
{
    use AuthorizesRequests;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('payroll.view');

        $query = Payroll::with(['employee.user'])
            ->when($request->user()->hasRole('employee'), function ($query) use ($request) {
                $query->whereHas('employee.user', function ($q) use ($request) {
                    $q->where('id', $request->user()->id);
                });
            });

        $payrolls = $query->paginate(10);
        return $this->successResponse($payrolls);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('payroll.process');

        DB::beginTransaction();
        try {
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
            DB::commit();
            return $this->successResponse($payroll, 'Payroll processed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to process payroll: ' . $e->getMessage());
        }
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

        $employees = $employeesQuery->with('user')->get();
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
                $netPay = $grossPay - $deductions['total'];

                $payrolls[] = Payroll::create([
                    'employee_id' => $employee->id,
                    'pay_period_start' => $request->pay_period_start,
                    'pay_period_end' => $request->pay_period_end,
                    'basic_pay' => $basicPay,
                    'overtime_pay' => $overtimePay,
                    'deductions' => $deductions['total'],
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
            'month' => 'nullable|integer|min:1|max:12',
            'include_trend' => 'nullable|boolean'
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

        // Add daily trend data if requested
        if ($request->boolean('include_trend')) {
            $trendQuery = Payroll::selectRaw('
                DATE(pay_period_start) as date,
                SUM(net_pay) as total,
                SUM(basic_pay) as basic_pay,
                SUM(overtime_pay) as overtime_pay,
                SUM(deductions) as deductions
            ')
            ->whereYear('pay_period_start', $request->year)
            ->when($request->has('month'), function($query) use ($request) {
                $query->whereMonth('pay_period_start', $request->month);
            })
            ->groupBy('date')
            ->orderBy('date');

            $statistics['daily_trend'] = $trendQuery->get();
        }

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
    private function calculateDeductions(Employee $employee, float $grossPay): array
    {
        // Tax brackets (simplified example)
        $taxBrackets = [
            ['limit' => 20000, 'rate' => 0.1],
            ['limit' => 40000, 'rate' => 0.15],
            ['limit' => 60000, 'rate' => 0.2],
            ['limit' => PHP_FLOAT_MAX, 'rate' => 0.25]
        ];

        // Calculate tax using brackets
        $remainingAmount = $grossPay;
        $tax = 0;
        foreach ($taxBrackets as $bracket) {
            if ($remainingAmount <= 0) break;
            
            $taxableAmount = min($remainingAmount, $bracket['limit']);
            $tax += $taxableAmount * $bracket['rate'];
            $remainingAmount -= $taxableAmount;
        }

        // Other standard deductions
        $sssDeduction = $grossPay * 0.045; // 4.5% SSS contribution
        $philhealthDeduction = $grossPay * 0.03; // 3% PhilHealth
        $pagibigDeduction = min(100, $grossPay * 0.02); // 2% Pag-IBIG capped at 100

        return [
            'tax' => $tax,
            'sss' => $sssDeduction,
            'philhealth' => $philhealthDeduction,
            'pagibig' => $pagibigDeduction,
            'total' => $tax + $sssDeduction + $philhealthDeduction + $pagibigDeduction
        ];
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

    /**
     * Process payroll for multiple employees
     */
    public function processBatch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $results = [
            'processed' => [],
            'skipped' => [],
            'errors' => []
        ];

        foreach ($request->employee_ids as $employeeId) {
            try {
                $employee = Employee::findOrFail($employeeId);
                
                // Check for existing payroll
                $exists = Payroll::where('employee_id', $employeeId)
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

                if ($exists) {
                    $results['skipped'][] = [
                        'employee_id' => $employeeId,
                        'reason' => 'Payroll already exists for this period'
                    ];
                    continue;
                }

                // Calculate payroll components
                $basicPay = $this->calculateBasicPay($employee->first(), $request->pay_period_start, $request->pay_period_end);
                $overtimePay = $this->calculateOvertimePay($employee->first(), $request->pay_period_start, $request->pay_period_end);
                $grossPay = $basicPay + $overtimePay;
                $deductions = $this->calculateDeductions($employee, $grossPay);
                $netPay = $grossPay - $deductions['total'];

                $payroll = Payroll::create([
                    'employee_id' => $employeeId,
                    'pay_period_start' => $request->pay_period_start,
                    'pay_period_end' => $request->pay_period_end,
                    'basic_pay' => $basicPay,
                    'overtime_pay' => $overtimePay,
                    'deductions' => $deductions['total'],
                    'net_pay' => $netPay,
                    'status' => 'pending'
                ]);

                $results['processed'][] = $payroll;

            } catch (\Exception $e) {
                $results['errors'][] = [
                    'employee_id' => $employeeId,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $this->successResponse($results, 'Batch processing completed');
    }

    /**
     * Calculate basic pay considering leaves and attendance
     */
    private function calculateBasicPay(Employee $employee, string $startDate, string $endDate): float
    {
        $workingDays = now()->parse($startDate)->diffInWeekdays($endDate);
        $dailyRate = $employee->salary / 22; // Assuming 22 working days per month

        // Get attendance records including leave status
        $attendanceRecords = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $paidDays = $attendanceRecords->filter(function ($attendance) {
            return in_array($attendance->status, ['present', 'late']) || 
                   ($attendance->status === 'on_leave' && $attendance->leave_type === 'paid');
        })->count();

        // Calculate pay based on attendance and paid leaves
        return $dailyRate * $paidDays;
    }

    /**
     * Get detailed payroll report for a department
     */
    public function departmentReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'year' => 'required|integer|min:2000|max:2099',
            'month' => 'required|integer|min:1|max:12'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $payrolls = Payroll::whereHas('employee', function($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->whereYear('pay_period_start', $request->year)
            ->whereMonth('pay_period_start', $request->month)
            ->with(['employee.user', 'employee.department'])
            ->get();

        $summary = [
            'total_payroll' => $payrolls->sum('net_pay'),
            'total_basic_pay' => $payrolls->sum('basic_pay'),
            'total_overtime' => $payrolls->sum('overtime_pay'),
            'total_deductions' => $payrolls->sum('deductions'),
            'average_salary' => $payrolls->avg('net_pay'),
            'employee_count' => $payrolls->count(),
            'status_breakdown' => $payrolls->groupBy('status')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'total' => $group->sum('net_pay')
                ])
        ];

        return $this->successResponse([
            'department' => $payrolls->first()?->employee->department,
            'period' => [
                'year' => $request->year,
                'month' => $request->month
            ],
            'summary' => $summary,
            'payrolls' => $payrolls
        ]);
    }

    /**
     * Forecast future payroll expenses
     */
    public function forecast(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'months_ahead' => 'required|integer|min:1|max:12',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        // Get historical payroll data
        $query = Payroll::with('employee')
            ->whereYear('pay_period_start', '>=', now()->subYear()->year);

        if ($request->has('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $historicalData = $query->get()
            ->groupBy(function($payroll) {
                return $payroll->pay_period_start->format('Y-m');
            })
            ->map(function($monthlyPayrolls) {
                return [
                    'total_payroll' => $monthlyPayrolls->sum('net_pay'),
                    'employee_count' => $monthlyPayrolls->count(),
                    'average_salary' => $monthlyPayrolls->avg('net_pay')
                ];
            });

        // Simple linear regression for forecasting
        $months = array_keys($historicalData->toArray());
        $values = $historicalData->pluck('total_payroll')->toArray();
        
        $n = count($months);
        $x = range(1, $n);
        $y = $values;
        
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = array_sum(array_map(function($x, $y) { return $x * $y; }, $x, $y));
        $sumX2 = array_sum(array_map(function($x) { return $x * $x; }, $x));
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        // Generate forecast
        $forecast = [];
        $lastMonth = end($months);
        $currentMonth = now()->parse($lastMonth);

        for ($i = 1; $i <= $request->months_ahead; $i++) {
            $forecastMonth = $currentMonth->copy()->addMonth($i);
            $forecastValue = $intercept + $slope * ($n + $i);
            
            $forecast[] = [
                'month' => $forecastMonth->format('Y-m'),
                'forecasted_payroll' => max(0, round($forecastValue, 2)),
                'confidence_range' => [
                    'low' => max(0, round($forecastValue * 0.9, 2)),
                    'high' => round($forecastValue * 1.1, 2)
                ]
            ];
        }

        return $this->successResponse([
            'historical_data' => $historicalData,
            'forecast' => $forecast,
            'metadata' => [
                'model_type' => 'Simple Linear Regression',
                'confidence_level' => '90%',
                'based_on_months' => count($historicalData)
            ]
        ]);
    }

    /**
     * Analyze payroll patterns and detect anomalies
     */
    public function analyze(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $query = Payroll::with(['employee.department', 'employee.user'])
            ->whereBetween('pay_period_start', [$request->start_date, $request->end_date]);

        if ($request->has('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $payrolls = $query->get();
        
        if ($payrolls->isEmpty()) {
            return $this->errorResponse('No payroll data found for the specified period', 404);
        }

        $analytics = new PayrollAnalytics();

        // Perform various analyses
        $anomalies = $analytics->detectAnomalies($payrolls);
        $timeSeriesAnalysis = $analytics->timeSeriesAnalysis($payrolls);
        $seasonalAnalysis = $analytics->seasonalDecomposition($payrolls);

        return $this->successResponse([
            'period' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ],
            'anomalies' => $anomalies,
            'time_series' => $timeSeriesAnalysis,
            'seasonal_patterns' => $seasonalAnalysis,
            'metadata' => [
                'total_records' => $payrolls->count(),
                'departments' => $payrolls->pluck('employee.department.name')->unique()
            ]
        ]);
    }

    public function approve(Payroll $payroll): JsonResponse
    {
        $this->authorize('payroll.approve');

        DB::beginTransaction();
        try {
            $payroll->update(['status' => 'approved']);
            DB::commit();
            return $this->successResponse($payroll, 'Payroll approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to approve payroll');
        }
    }
} 