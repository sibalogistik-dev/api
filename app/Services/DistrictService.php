<?php

namespace App\Services;

use App\Models\Cabang;
use App\Models\District;
use App\Models\Village;
use Exception;
use Illuminate\Support\Facades\DB;

class DistrictService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $district = District::create($data);
            DB::commit();
            return $district;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save branch data: ' . $e->getMessage());
        }
    }

    public function update(District $district, array $data)
    {
        DB::beginTransaction();
        try {
            $district->update($data);
            DB::commit();
            return $district;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch data: ' . $e->getMessage());
        }
    }
}
