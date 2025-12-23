<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetMaintenanceIndexRequest;
use App\Http\Requests\AssetMaintenanceStoreRequest;
use App\Http\Requests\AssetMaintenanceUpdateRequest;
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

    public function index(AssetMaintenanceIndexRequest $request)
    {
        //
    }

    public function store(AssetMaintenanceStoreRequest $request)
    {
        //
    }

    public function show($assetMaintenance)
    {
        //
    }

    public function update(AssetMaintenanceUpdateRequest $request, $assetMaintenance)
    {
        //
    }

    public function destroy($assetMaintenance)
    {
        //
    }
}
