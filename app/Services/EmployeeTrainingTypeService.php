<?php

namespace App\Services;

use App\Models\EmployeeTrainingType;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeTrainingTypeService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $et = EmployeeTrainingType::create($data);
            DB::commit();
            return $et;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee training type data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeTrainingType $employeeTrainingType, array $data)
    {
        DB::beginTransaction();
        try {
            $employeeTrainingType->update($data);
            DB::commit();
            return $employeeTrainingType;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee training type data: ' . $e->getMessage());
        }
    }
}
