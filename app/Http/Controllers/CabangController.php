<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\BranchIndexRequest;
use App\Http\Requests\BranchStoreRequest;
use App\Models\Cabang;
use App\Services\BranchService;
use Exception;
use Illuminate\Http\Request;
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
        $validated = $request->validated();
        $branchQuery = Cabang::query()
            ->filter($validated)
            ->orderBy('id', 'desc');

        $branch = isset($validated['paginate']) && $validated['paginate'] ? $branchQuery->paginate($validated['perPage'] ?? 10) : $branchQuery->get();

        $itemsToTransform = $branch instanceof LengthAwarePaginator ? $branch->getCollection() : $branch;

        $transformedBranch = $itemsToTransform->map(function ($item) {
            return [
                'id'        => $item->id,
                'name'      => $item->name,
                'address'   => $item->address,
                'telephone' => $item->telephone ?? null,
                'city'      => $item->city->name,
                'province'  => $item->city->province->name,
            ];
        });

        if ($branch instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Branches list', $branch->setCollection($transformedBranch));
        } else {
            return ApiResponseHelper::success('Branches list', $transformedBranch);
        }
    }

    public function create()
    {
        //
    }

    public function store(BranchStoreRequest $request)
    {
        try {
            $cabang = $this->branchService->create($request->validated());
            return ApiResponseHelper::success('Employee data has been added successfully', $cabang);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving branch data', $e->getMessage(), 500);
        }
    }

    public function show($cabang)
    {
        $data = Cabang::find($cabang);
        if ($data) {
            return ApiResponseHelper::success("Branch's Detail", $data);
        } else {
            return ApiResponseHelper::success("Branch's Detail", []);
        }
    }

    public function update(Request $request, Cabang $cabang)
    {
        try {
            $this->branchService->update($cabang, $request->validated());
            return ApiResponseHelper::success('Branch data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating branch data', $e->getMessage(), 500);
        }
    }

    public function destroy(Cabang $cabang)
    {
        $delete = $cabang->delete();
        if ($delete) {
            return ApiResponseHelper::success('Branch data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Branch data failed to delete', null, 500);
        }
    }
}
