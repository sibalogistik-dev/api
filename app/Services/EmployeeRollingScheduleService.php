<?php

namespace App\Services;

use App\Models\EmployeeRollingSchedule;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeRollingScheduleService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $et = EmployeeRollingSchedule::create($data);
            DB::commit();
            return $et;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee rolling schedule data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeRollingSchedule $employeeEvaluation, array $data)
    {
        DB::beginTransaction();
        try {
            $employeeEvaluation->update($data);
            DB::commit();
            return $employeeEvaluation;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee rolling schedule data: ' . $e->getMessage());
        }
    }
}
