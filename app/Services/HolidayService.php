<?php

namespace App\Services;

use App\Models\Holiday;
use Exception;
use Illuminate\Support\Facades\DB;

class HolidayService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $holiday = Holiday::create($data);
            DB::commit();
            return $holiday;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save holiday data: ' . $e->getMessage());
        }
    }

    public function update(Holiday $holiday, array $data)
    {
        DB::beginTransaction();
        try {
            $holiday->update($data);
            DB::commit();
            return $holiday;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update holiday data: ' . $e->getMessage());
        }
    }
}
