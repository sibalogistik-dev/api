<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\BranchIndexRequest;
use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Models\Cabang;
use App\Services\BranchService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class CabangController extends Controller
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(BranchIndexRequest $request)
    {
        try {
            $validated = $request->validated();
            $branchQ = Cabang::query()->with(['company', 'village.district.city.province'])->filter($validated);
            $branch = isset($validated['paginate']) && $validated['paginate'] ? $branchQ->paginate($validated['perPage'] ?? 10) : $branchQ->get();
            $transformedItems = $branch instanceof LengthAwarePaginator ? $branch->getCollection() : $branch;
            $transformedBranch = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'address'       => $item->address,
                    'telephone'     => $item->telephone ?? null,
                    'village_id'    => $item->village_id,
                    'province'      => $item->village?->district?->city?->province?->name,
                    'city'          => $item->village?->district?->city?->name,
                    'district'      => $item->village?->district?->name,
                    'village'       => $item->village?->name,
                ];
            });

            if ($branch instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Branches list', $branch->setCollection($transformedBranch));
            } else {
                return ApiResponseHelper::success('Branches list', $transformedBranch);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::success('Failed to get branch data', $e->getMessage());
        }
    }

    public function store(BranchStoreRequest $request)
    {
        try {
            $cabang = $this->branchService->create($request->validated());
            return ApiResponseHelper::success('Branch data has been added successfully', $cabang);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving branch data', $e->getMessage());
        }
    }

    public function show($branch)
    {
        try {
            $cabang = Cabang::find($branch);
            if (!$cabang) {
                throw new Exception('Branch data not found');
            }
            return ApiResponseHelper::success("Branch's detail", $cabang);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get branch data", $e->getMessage());
        }
    }

    public function update(Cabang $branch, BranchUpdateRequest $request)
    {
        try {
            $this->branchService->update($branch, $request->validated());
            return ApiResponseHelper::success('Branch data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating branch data', $e->getMessage());
        }
    }

    public function destroy($branch)
    {
        try {
            $branch = Cabang::find($branch);
            if (!$branch) {
                throw new Exception('Branch data not found');
            }
            $delete = $branch->delete();
            if (!$delete) {
                throw new Exception('Failed to delete branch data');
            }
            return ApiResponseHelper::success('Branch data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Branch data failed to delete', $e->getMessage());
        }
    }
}
