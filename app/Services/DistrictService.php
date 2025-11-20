<?php

namespace App\Services;

use App\Models\District;
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
            throw new Exception('Failed to save district data: ' . $e->getMessage());
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
            throw new Exception('Failed to update district data: ' . $e->getMessage());
        }
    }
}
