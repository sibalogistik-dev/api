<?php

namespace App\Services;

use App\Models\EmployeeTrainingSchedule;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeTrainingScheduleService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $et = EmployeeTrainingSchedule::create($data);
            DB::commit();
            return $et;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee\'s training schedule data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeTrainingSchedule $employeeTrainingSchedule, array $data)
    {
        DB::beginTransaction();
        try {
            $employeeTrainingSchedule->update($data);
            DB::commit();
            return $employeeTrainingSchedule;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee\'s training schedule data: ' . $e->getMessage());
        }
    }
}
