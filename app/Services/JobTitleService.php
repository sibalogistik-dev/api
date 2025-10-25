<?php

namespace App\Services;

use App\Models\Jabatan;
use Exception;
use Illuminate\Support\Facades\DB;

class JobTitleService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $jobTitle = Jabatan::create($data);
            DB::commit();
            return $jobTitle;
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
