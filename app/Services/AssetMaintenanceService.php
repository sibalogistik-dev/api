<?php

namespace App\Services;

use App\Models\Agama;
use App\Models\AssetMaintenance;
use Exception;
use Illuminate\Support\Facades\DB;

class AssetMaintenanceService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return AssetMaintenance::create($data);
        });
    }

    public function update(AssetMaintenance $assetMaintenance, array $data)
    {
        return DB::transaction(function () use ($assetMaintenance, $data) {
            $assetMaintenance->update($data);
            return $assetMaintenance;
        });
    }
}
