<?php

namespace App\Services;

use App\Models\EmployeeDailyReport;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeDailyReportService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $employeeDailyReport = EmployeeDailyReport::create($data);
            DB::commit();
            return $employeeDailyReport;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee\'s daily report data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeDailyReport $EmployeeDailyReport, array $data)
    {
        DB::beginTransaction();
        try {
            $EmployeeDailyReport->update($data);
            DB::commit();
            return $EmployeeDailyReport;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee\'s daily report data: ' . $e->getMessage());
        }
    }
}
