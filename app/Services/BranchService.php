<?php

namespace App\Services;

use App\Models\Cabang;
use Exception;
use Illuminate\Support\Facades\DB;

class BranchService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $cabang = Cabang::create($data);
            DB::commit();
            return $cabang;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save branch data: ' . $e->getMessage());
        }
    }

    public function update(Cabang $cabang, array $data)
    {
        DB::beginTransaction();
        try {
            $cabang->update($data);
            DB::commit();
            return $cabang;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch data: ' . $e->getMessage());
        }
    }
}
