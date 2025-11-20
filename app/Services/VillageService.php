<?php

namespace App\Services;

use App\Models\Village;
use Exception;
use Illuminate\Support\Facades\DB;

class VillageService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $village = Village::create($data);
            DB::commit();
            return $village;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save branch data: ' . $e->getMessage());
        }
    }

    public function update(Village $village, array $data)
    {
        DB::beginTransaction();
        try {
            $village->update($data);
            DB::commit();
            return $village;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch data: ' . $e->getMessage());
        }
    }
}
