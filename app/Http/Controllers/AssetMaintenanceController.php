<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AssetMaintenanceIndexRequest;
use App\Http\Requests\AssetMaintenanceStoreRequest;
use App\Http\Requests\AssetMaintenanceUpdateRequest;
use App\Models\AssetMaintenance;
use App\Services\AssetMaintenanceService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

class AssetMaintenanceController extends Controller
{
    protected $assetMaintenanceService;

    public function __construct(AssetMaintenanceService $assetMaintenanceService)
    {
        $this->assetMaintenanceService = $assetMaintenanceService;
        $this->middleware('permission:hrd.asset-maintenance|hrd.asset-maintenance.index', ['only' => ['index']]);
        $this->middleware('permission:hrd.asset-maintenance|hrd.asset-maintenance.show', ['only' => ['show']]);
        $this->middleware('permission:hrd.asset-maintenance|hrd.asset-maintenance.store', ['only' => ['store']]);
        $this->middleware('permission:hrd.asset-maintenance|hrd.asset-maintenance.update', ['only' => ['update']]);
        $this->middleware('permission:hrd.asset-maintenance|hrd.asset-maintenance.destroy', ['only' => ['destroy']]);
    }

    public function index(AssetMaintenanceIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $assetMaintenanceQ  = AssetMaintenance::query()->filter($validated);
            $assetMaintenance   = isset($validated['paginate']) && $validated['paginate'] ? $assetMaintenanceQ->paginate($validated['perPage'] ?? 10) : $assetMaintenanceQ->get();
            $tranformItems      = $assetMaintenance instanceof LengthAwarePaginator ? $assetMaintenance->getCollection() : $assetMaintenance;
            $transformedAM      = $tranformItems->map(function ($item) {
                return [
                    'id'                        => $item->id,
                    'asset_id'                  => $item->asset_id,
                    'asset_name'                => $item->asset->name,
                    'asset_branch_name'         => $item->asset->branch->name,
                    'creator_id'                => $item->creator_id,
                    'creator_name'              => $item->creator->name,
                    'maintenance_date'          => $item->maintenance_date,
                    'min_maintenance_cost'      => $item->min_maintenance_cost,
                    'max_maintenance_cost'      => $item->max_maintenance_cost,
                    'actual_maintenance_cost'   => $item->actual_maintenance_cost,
                    'description'               => $item->description,
                    'approval_status'           => $item->approval_status,
                    'receipt'                   => $item->receipt,
                ];
            });
            if ($assetMaintenance instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Asset maintenance data', $assetMaintenance->setCollection($transformedAM));
            } else {
                return ApiResponseHelper::success('Asset maintenance data', $transformedAM);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get asset maintenance data', $e->getMessage());
        }
    }

    public function store(AssetMaintenanceStoreRequest $request)
    {
        try {
            $assetMaintenance = $this->assetMaintenanceService->create($request->validated());
            return ApiResponseHelper::success('Asset maintenance data has been added successfully', $assetMaintenance);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving asset maintenance data', $e->getMessage());
        }
    }

    public function show($assetMaintenance)
    {
        try {
            $assetM = AssetMaintenance::findOrFail($assetMaintenance);
            return ApiResponseHelper::success('Asset maintenance detail', $assetM);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get asset maintenance detail', $e->getMessage());
        }
    }

    public function update(AssetMaintenanceUpdateRequest $request, $assetMaintenance)
    {
        try {
            $assetM = AssetMaintenance::findOrFail($assetMaintenance);
            $this->assetMaintenanceService->update($assetM, $request->validated());
            return ApiResponseHelper::success('Asset maintenance data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating asset maintenance data', $e->getMessage());
        }
    }

    public function destroy($assetMaintenance)
    {
        try {
            AssetMaintenance::findOrFail($assetMaintenance)->delete();
            return ApiResponseHelper::success('Asset maintenance data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting asset maintenance data', $e->getMessage());
        }
    }
}
