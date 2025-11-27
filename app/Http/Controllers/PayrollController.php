<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\PayrollIndexRequest;
use App\Http\Requests\PayrollStoreRequest;
use App\Http\Requests\PayrollUpdateRequest;
use App\Models\Payroll;
use App\Services\PayrollService;
use Exception;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(PayrollIndexRequest $request)
    {
        try {
            $validated  = $request->validated();
            $payrollQ   = Payroll::query()->filter($validated)->orderBy('period_start');
            $payroll    = isset($validated['paginate']) && $validated['paginate'] ? $payrollQ->paginate($validated['perPage'] ?? 10) : $payrollQ->get();
            return ApiResponseHelper::success('Payroll list', $payroll);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get payroll data', $e->getMessage());
        }
    }

    public function store(PayrollStoreRequest $request)
    {
        try {
            $payroll = $this->payrollService->create($request->validated());
            return ApiResponseHelper::success('Payroll data has been added successfully', $payroll);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving payroll data', $e->getMessage());
        }
    }

    public function show($payroll)
    {
        try {
            $payroll    = Payroll::find($payroll);
            if (!$payroll) {
                throw new Exception('Payroll data not found');
            }
            $data = [
                'id'                => $payroll->id,
                'employee_id'       => $payroll->employee_id,
                'employee_name'     => $payroll->employee->name,
                'period_name'       => $payroll->period_name,
                'period_start'      => $payroll->period_start,
                'period_end'        => $payroll->period_end,
                'salary_type'       => $payroll->salary_type,
                'base_salary'       => $payroll->base_salary,
                'days'              => $payroll->days,
                'present_days'      => $payroll->present_days,
                'half_days'         => $payroll->half_days,
                'absent_days'       => $payroll->absent_days,
                'sick_days'         => $payroll->sick_days,
                'leave_days'        => $payroll->leave_days,
                'permission_days'   => $payroll->permission_days,
                'off_days'          => $payroll->off_days,
                'overtime_minutes'  => $payroll->overtime_minutes,
                'late_minutes'      => $payroll->late_minutes,
                'deductions'        => $payroll->deductions,
                'allowances'        => $payroll->allowances,
                'overtime'          => $payroll->overtime,
                'compensation'      => $payroll->compensation,
                'net_salary'        => $payroll->net_salary,
                'generated_at'      => $payroll->generated_at,
            ];
            return ApiResponseHelper::success("Payroll data", $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get payroll data', $e->getMessage());
        }
    }

    public function update(PayrollUpdateRequest $request, Payroll $payroll)
    {
        try {
            $this->payrollService->update($payroll, $request->validated());
            return ApiResponseHelper::success('Payroll data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating payroll data', $e->getMessage());
        }
    }

    public function destroy($payroll)
    {
        try {
            $payroll = Payroll::find($payroll);
            if (!$payroll) {
                throw new Exception('Payroll data not found');
            }
            $delete = $payroll->delete();
            if (!$delete) {
                throw new Exception('Failed to delete payroll data');
            }
            return ApiResponseHelper::success('Payroll data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting payroll data', $e->getMessage());
        }
    }

    public function generatePayrollPersonal($employee)
    {
        // 
    }
}
