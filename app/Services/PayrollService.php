<?php

namespace App\Services;

use App\Console\Commands\PayrollGeneration;
use App\Models\Karyawan;
use App\Models\Payroll;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function create(array $data)
    {
        $month = $data['month'] ?? now()->format('Y-m');

        try {
            Artisan::call('app:payroll-generation', ['month' => $month]);

            $periodStart = Carbon::parse("$month-28")->subMonth()->startOfDay();
            $payrollData = Payroll::where('period_start', $periodStart)->get();

            return $payrollData;
        } catch (Exception $e) {
            throw new Exception('Failed to generate payroll: ' . $e->getMessage());
        }
    }

    public function update(Payroll $payroll, array $data)
    {
        return DB::transaction(function () use ($payroll, $data) {
            try {
                $payroll->update($data);
                return $payroll;
            } catch (Exception $e) {
                throw new Exception('Failed to update payroll data: ' . $e->getMessage());
            }
        });
    }

    public function generatePayrollReport(array $data)
    {
        DB::beginTransaction();
        try {
            $periodMonth    = Carbon::parse($data['month'] ?? now())->format('m');
            $periodYear     = Carbon::parse($data['month'] ?? now())->format('Y');

            $data = Payroll::whereMonth('period_end', $periodMonth)
                ->whereYear('period_end', $periodYear)
                ->get();
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to generate payroll report: ' . $e->getMessage());
        }
    }

    public function generatePayrollSlip(array $data)
    {
        DB::beginTransaction();
        try {
            $payroll        = Payroll::find($data['payroll_id']);
            if (!$payroll) {
                throw new Exception('Payroll data not found');
            }
            DB::commit();
            return $payroll->load('employee.jobTitle', 'employee.branch.company', 'employee.branch.village.district.city.province');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to generate payroll slip: ' . $e->getMessage());
        }
    }

    public function calculatePayrollPersonal($employee, array $data)
    {
        $month = $data['month'] ?? now()->format('Y-m');

        try {
            Artisan::call('app:payroll-generation', ['month' => $month, 'employee_id' => $employee->id]);

            $periodStart = Carbon::parse("$month-28")->subMonth()->startOfDay();
            $payrollData = Payroll::where('period_start', $periodStart)->get();

            return $payrollData;
        } catch (Exception $e) {
            throw new Exception('Failed to generate payroll: ' . $e->getMessage());
        }
    }
}
