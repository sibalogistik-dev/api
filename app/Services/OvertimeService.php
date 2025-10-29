<?php

namespace App\Services;

use App\Models\Overtime;
use Exception;
use Illuminate\Support\Facades\DB;

class OvertimeService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $overtime = Overtime::create($data);
            DB::commit();
            return $overtime;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save overtime data: ' . $e->getMessage());
        }
    }

    public function update(Overtime $overtime, array $data)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            return $overtime;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update overtime data: ' . $e->getMessage());
        }
    }
}
