<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\DistrictIndexRequest;
use App\Http\Requests\DistrictStoreRequest;
use App\Models\District;
use App\Services\DistrictService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KecamatanController extends Controller
{
    protected $districtService;

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    public function index(DistrictIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $districtQuery          = District::query()->filter($validated);
            $district               = isset($validated['paginate']) && $validated['paginate'] ? $districtQuery->paginate($validated['perPage'] ?? 10) : $districtQuery->get();
            $itemsToTransform       = $district instanceof LengthAwarePaginator ? $district->getCollection() : $district;
            $transformedDistrict    = $itemsToTransform->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                ];
            });
            if ($district instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('District list', $district->setCollection($transformedDistrict));
            } else {
                return ApiResponseHelper::success('District list', $transformedDistrict);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get district data', $e->getMessage());
        }
    }

    public function store(DistrictStoreRequest $request)
    {
        try {
            $district = $this->districtService->create($request->validated());
            return ApiResponseHelper::success('District data has been added successfully', $district);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving district data', $e->getMessage(), 500);
        }
    }

    public function show($district)
    {
        $district = District::find($district);
        if (!$district) {
            return ApiResponseHelper::error('District not found', [], 404);
        }
        return ApiResponseHelper::success('District data', $district);
    }

    public function update(Request $request,  $district)
    {
        try {
            $district = District::find($district);
            if (!$district) {
                throw new Exception('District not found');
            }
            $this->districtService->update($district, $request->validated());
            return ApiResponseHelper::success('District data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating district data', $e->getMessage(), 500);
        }
    }

    public function destroy($district)
    {
        try {
            $district = District::find($district);
            if (!$district) {
                throw new Exception('District not found', 404);
            }

            $delete = $district->delete();
            if (!$delete) {
                throw new Exception('Failed to delete district data', 404);
            }
            return ApiResponseHelper::success('District data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('District data failed to delete', $e->getMessage(), $e->getCode());
        }
    }
}
