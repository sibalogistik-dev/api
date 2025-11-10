<?php

namespace App\Services;

use App\Models\MarriageStatus;
use Exception;
use Illuminate\Support\Facades\DB;

class MarriageStatusService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $marriageStatus = MarriageStatus::create($data);
            DB::commit();
            return $marriageStatus;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save marriage status data: ' . $e->getMessage());
        }
    }

    public function update(MarriageStatus $marriageStatus, array $data)
    {
        DB::beginTransaction();
        try {
            $marriageStatus->update($data);
            DB::commit();
            return $marriageStatus;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update marriage status data: ' . $e->getMessage());
        }
    }
}
