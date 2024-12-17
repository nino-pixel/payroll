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
} 