<?php

namespace App\Services;

use App\Models\Province;
use Exception;
use Illuminate\Support\Facades\DB;

class DistrictService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $province = Province::create($data);
            DB::commit();
            return $province;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save province data: ' . $e->getMessage());
        }
    }

    public function update(Province $province, array $data)
    {
        DB::beginTransaction();
        try {
            $province->update($data);
            DB::commit();
            return $province;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update province data: ' . $e->getMessage());
        }
    }
}
