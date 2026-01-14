<?php

namespace App\Services;

use App\Models\EmployeeEvaluation;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeEvaluationService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $et = EmployeeEvaluation::create($data);
            DB::commit();
            return $et;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee evaluation data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeEvaluation $employeeEvaluation, array $data)
    {
        DB::beginTransaction();
        try {
            $employeeEvaluation->update($data);
            DB::commit();
            return $employeeEvaluation;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee evaluation data: ' . $e->getMessage());
        }
    }

    public function report(array $data)
    {
        DB::beginTransaction();
        try {
            $response = EmployeeEvaluation::query()
                ->filter($data)
                ->get();
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate employee evaluation report: ' . $e->getMessage());
        }
    }
}
