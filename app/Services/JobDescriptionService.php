<?php

namespace App\Services;

use App\Models\JobDescription;
use Exception;
use Illuminate\Support\Facades\DB;

class JobDescriptionService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $jobDescription = JobDescription::create($data);
            DB::commit();
            return $jobDescription;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save job description data: ' . $e->getMessage());
        }
    }

    public function update(JobDescription $jobDescription, array $data)
    {
        DB::beginTransaction();
        try {
            
            $jobDescription->update($data);
            DB::commit();
            return $jobDescription;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update job description data: ' . $e->getMessage());
        }
    }
}
