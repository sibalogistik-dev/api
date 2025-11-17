<?php

namespace App\Services;

use App\Models\Pendidikan;
use Exception;
use Illuminate\Support\Facades\DB;

class EducationService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $education = Pendidikan::create($data);
            DB::commit();
            return $education;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save company data: ' . $e->getMessage());
        }
    }

    public function update(Pendidikan $education, array $data)
    {
        DB::beginTransaction();
        try {
            $education->update($data);
            DB::commit();
            return $education;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update company data: ' . $e->getMessage());
        }
    }
}
