<?php

namespace App\Services;

use App\Models\EmployeeTraining;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeTrainingService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $et = EmployeeTraining::create($data);
            DB::commit();
            return $et;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee training data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeTraining $employeeTraining, array $data)
    {
        DB::beginTransaction();
        try {
            $employeeTraining->update($data);
            DB::commit();
            return $employeeTraining;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee training data: ' . $e->getMessage());
        }
    }

    public function report(array $data)
    {
        DB::beginTransaction();
        try {
            $response = EmployeeTraining::query()
                ->filter($data)
                ->get();
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate employee training report: ' . $e->getMessage());
        }
    }

    public function document(array $data)
    {
        DB::beginTransaction();
        try {
            $response = EmployeeTraining::find($data['employee_training_id']);
            if (!$response) {
                throw new Exception('Employee training data not found');
            }
            $response->load('employee', 'trainingType', 'schedules.mentor');
            $response->schedules()->orderBy('start_date', 'asc');
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate employee training document: ' . $e->getMessage());
        }
    }
}
