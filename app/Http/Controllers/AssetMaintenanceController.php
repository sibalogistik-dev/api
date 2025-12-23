<?php

namespace App\Http\Controllers;

use App\Models\AssetMaintenance;
use App\Services\AssetMaintenanceService;
use Illuminate\Http\Request;

class AssetMaintenanceController extends Controller
{
    protected $assetMaintenanceService;

    public function __construct(AssetMaintenanceService $assetMaintenanceService)
    {
        $this->assetMaintenanceService = $assetMaintenanceService;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(AssetMaintenance $assetMaintenance)
    {
        //
    }

    public function update(Request $request, AssetMaintenance $assetMaintenance)
    {
        //
    }

    public function destroy(AssetMaintenance $assetMaintenance)
    {
        //
    }
}
