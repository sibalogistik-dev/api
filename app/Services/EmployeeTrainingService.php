<?php

namespace App\Services;

use App\Models\EmployeeTraining;
use App\Models\Jabatan;
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
            throw new Exception('Failed to save job title data: ' . $e->getMessage());
        }
    }

    public function update(Jabatan $jabatan, array $data)
    {
        DB::beginTransaction();
        try {
            $jabatan->update($data);
            DB::commit();
            return $jabatan;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update job title data: ' . $e->getMessage());
        }
    }
}
