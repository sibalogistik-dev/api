<?php

namespace App\Services;

use App\Models\City;
use Exception;
use Illuminate\Support\Facades\DB;

class CityService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $city = City::create($data);
            DB::commit();
            return $city;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save city data: ' . $e->getMessage());
        }
    }

    public function update(City $city, array $data)
    {
        DB::beginTransaction();
        try {
            $city->update($data);
            DB::commit();
            return $city;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update city data: ' . $e->getMessage());
        }
    }
}
