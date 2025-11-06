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
        $validated      = $request->validated();
        $payrollQuery   = Payroll::query()->filter($validated)->orderBy('id');
        $payroll        = isset($validated['paginate']) && $validated['paginate'] ? $payrollQuery->paginate($validated['perPage'] ?? 10) : $payrollQuery->get();
        return ApiResponseHelper::success('Payroll list', $payroll);
    }

    public function store(PayrollStoreRequest $request)
    {
        try {
            $payroll = $this->payrollService->create($request->validated());
            return ApiResponseHelper::success('Payroll data has been added successfully', $payroll);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving payroll data', $e->getMessage(), 500);
        }
    }

    public function show($payroll)
    {
        $payroll = Payroll::find($payroll);
        if (!$payroll) {
            return ApiResponseHelper::error('Company not found', [], 404);
        }
        $data = [
            'id'                => $payroll->id,
            'employee_id'       => $payroll->employee_id,
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
        return ApiResponseHelper::success("Payroll's detail", $data);
    }

    public function update(PayrollUpdateRequest $request, Payroll $payroll)
    {
        try {
            $this->payrollService->update($payroll, $request->validated());
            return ApiResponseHelper::success('Payroll data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating payroll data', $e->getMessage(), 500);
        }
    }

    public function destroy($payroll)
    {
        try {
            $payroll = Payroll::find($payroll);
            if (!$payroll) {
                return ApiResponseHelper::error('Payroll data not found', null, 404);
            }
            $delete = $payroll->delete();
            if (!$delete) {
                return ApiResponseHelper::error('Payroll data failed to delete', null, 500);
            }
            return ApiResponseHelper::success('Payroll data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Payroll data failed to delete', $e->getMessage(), 500);
        }
    }
}
