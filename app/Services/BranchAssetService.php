<?php

namespace App\Services;

use App\Models\BranchAsset;
use Exception;
use Illuminate\Support\Facades\DB;

class BranchAssetService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $branchAsset = BranchAsset::create($data);
            DB::commit();
            return $branchAsset;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save branch\'s asset data: ' . $e->getMessage());
        }
    }

    public function update(BranchAsset $branchAsset, array $data)
    {
        DB::beginTransaction();
        try {
            $branchAsset->update($data);
            DB::commit();
            return $branchAsset;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch\'s asset data: ' . $e->getMessage());
        }
    }
}
