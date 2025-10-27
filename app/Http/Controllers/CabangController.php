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
        $validated          = $request->validated();
        $branchQuery        = Cabang::query()->filter($validated)->orderBy('id', 'desc');
        $branch             = isset($validated['paginate']) && $validated['paginate'] ? $branchQuery->paginate($validated['perPage'] ?? 10) : $branchQuery->get();
        $itemsToTransform   = $branch instanceof LengthAwarePaginator ? $branch->getCollection() : $branch;
        $transformedBranch  = $itemsToTransform->map(function ($item) {
            return [
                'id'        => $item->id,
                'name'      => $item->name,
                'address'   => $item->address,
                'telephone' => $item->telephone ?? null,
                'province'  => $item->village->district->city->province->name,
                'city'      => $item->village->district->city->name,
                'district'  => $item->village->district->name,
                'village'   => $item->village->name,
            ];
        });
        if ($branch instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Branches list', $branch->setCollection($transformedBranch));
        } else {
            return ApiResponseHelper::success('Branches list', $transformedBranch);
        }
    }

    public function store(BranchStoreRequest $request)
    {
        try {
            $cabang = $this->branchService->create($request->validated());
            return ApiResponseHelper::success('Branch data has been added successfully', $cabang);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving branch data', $e->getMessage(), 500);
        }
    }

    public function show($branch)
    {
        $cabang = Cabang::find($branch);
        if (!$cabang) {
            return ApiResponseHelper::error("Branch not found", [], 404);
        }
        return ApiResponseHelper::success("Branch's detail", $branch);
    }

    public function update(Cabang $branch, BranchUpdateRequest $request)
    {
        try {
            $this->branchService->update($branch, $request->validated());
            return ApiResponseHelper::success('Branch data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating branch data', $e->getMessage(), 500);
        }
    }

    public function destroy($branch)
    {
        $branch = Cabang::find($branch);
        if (!$branch) {
            return ApiResponseHelper::error('Branch not found', [], 404);
        }

        $delete = $branch->delete();
        if ($delete) {
            return ApiResponseHelper::success('Branch data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Branch data failed to delete', null, 500);
        }
    }
}
