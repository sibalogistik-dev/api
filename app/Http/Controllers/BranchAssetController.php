<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\BranchAssetIndexRequest;
use App\Http\Requests\BranchAssetReportRequest;
use App\Http\Requests\BranchAssetStoreRequest;
use App\Http\Requests\BranchAssetUpdateRequest;
use App\Models\BranchAsset;
use App\Services\BranchAssetService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchAssetController extends Controller
{
    protected $branchAssetService;

    public function __construct(BranchAssetService $branchAssetService)
    {
        $this->branchAssetService = $branchAssetService;
    }

    public function index(BranchAssetIndexRequest $request)
    {
        try {
            $validated      = $request->validated();
            $baQ            = BranchAsset::query()->filter($validated);
            $ba             = isset($validated['paginate']) && $validated['paginate'] ? $baQ->paginate($validated['perPage'] ?? 10) : $baQ->get();
            $tranformItems  = $ba instanceof LengthAwarePaginator ? $ba->getCollection() : $ba;
            $transformedBA  = $tranformItems->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'branch_id'         => $item->branch_id,
                    'branch_name'       => $item->branch->name,
                    'asset_type_id'     => $item->asset_type_id,
                    'asset_type_name'   => $item->assetType->name,
                    'is_vehicle'        => $item->is_vehicle,
                    'name'              => $item->name,
                    'price'             => $item->price,
                    'quantity'          => $item->quantity,
                    'image_path'        => $item->image_path,
                    'purchase_date'     => $item->purchase_date,
                    'description'       => $item->description,
                ];
            });
            if ($ba instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Branch\'s asset data', $ba->setCollection($transformedBA));
            } else {
                return ApiResponseHelper::success('Branch\'s asset data', $transformedBA);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get branch\'s asset data', $e->getMessage());
        }
    }

    public function store(BranchAssetStoreRequest $request)
    {
        try {
            $branchAsset = $this->branchAssetService->create($request->validated());
            return ApiResponseHelper::success('Branch\'s asset data has been added successfully', $branchAsset);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving branch\'s asset data', $e->getMessage());
        }
    }

    public function show($branchAsset)
    {
        try {
            $q = BranchAsset::with(['branch', 'assetType'])->findOrFail($branchAsset);
            if (!$q) {
                throw new Exception('Branch\'s asset data not found');
            }
            $data = [
                'id'                => $q->id,
                'branch_id'         => $q->branch_id,
                'branch_name'       => $q->branch->name,
                'asset_type_id'     => $q->asset_type_id,
                'asset_type_name'   => $q->assetType->name,
                'is_vehicle'        => $q->is_vehicle,
                'name'              => $q->name,
                'quantity'          => $q->quantity,
                'image_path'        => $q->image_path,
                'price'             => $q->price,
                'purchase_date'     => $q->purchase_date,
                'description'       => $q->description,
            ];
            return ApiResponseHelper::success('Branch\'s asset data', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::success('Failed to get branch\'s asset data', $e->getMessage());
        }
    }

    public function update(BranchAssetUpdateRequest $request, $branchAsset)
    {
        try {
            $q = BranchAsset::find($branchAsset);
            if (!$q) {
                throw new Exception('Branch\'s asset data not found');
            }
            $this->branchAssetService->update($q, $request->validated());
            return ApiResponseHelper::success('Branch\'s asset data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating branch\'s asset data', $e->getMessage());
        }
    }

    public function destroy($branchAsset)
    {
        try {
            $q = BranchAsset::find($branchAsset);
            if (!$q) {
                throw new Exception('Branch\'s asset data not found');
            }
            $delete = $q->delete();
            if (!$delete) {
                throw new Exception('Failed to delete branch\'s asset data');
            }
            return ApiResponseHelper::success('Branch\'s asset data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting branch\'s asset data', $e->getMessage());
        }
    }

    public function report(BranchAssetReportRequest $request)
    {
        try {
            $validated  = $request->validated();
            $report     = $this->branchAssetService->report($validated);
            $start      = $validated['start_date'] ?? null;
            $end        = $validated['end_date'] ?? null;
            $pdf        = Pdf::loadView('branch-asset.report', compact('report', 'start', 'end'))->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan Aset Perusahaan.pdf');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when branch asset report', $e->getMessage());
        }
    }
}
