<?php

namespace App\Services;

use App\Models\Resign;
use Exception;
use Illuminate\Support\Facades\DB;

class ResignService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $resign = Resign::create($data);
            DB::commit();
            return $resign;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save resign data: ' . $e->getMessage());
        }
    }

    public function update(Resign $resign, array $data)
    {
        DB::beginTransaction();
        try {
            $resign->update($data);
            DB::commit();
            return $resign;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update resign data: ' . $e->getMessage());
        }
    }
}
