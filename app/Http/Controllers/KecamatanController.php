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
            $districtQ          = District::query()->filter($validated);
            $district               = isset($validated['paginate']) && $validated['paginate'] ? $districtQ->paginate($validated['perPage'] ?? 10) : $districtQ->get();
            $itemsToTransform       = $district instanceof LengthAwarePaginator ? $district->getCollection() : $district;
            $transformedDistrict    = $itemsToTransform->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'code'  => $item->code,
                ];
            });
            if ($district instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('District list', $district->setCollection($transformedDistrict));
            } else {
                return ApiResponseHelper::success('District list', $transformedDistrict);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get district data');
        }
    }

    public function store(DistrictStoreRequest $request)
    {
        try {
            $district = $this->districtService->create($request->validated());
            return ApiResponseHelper::success('District data has been added successfully', $district);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving district data', $e->getMessage());
        }
    }

    public function show($district)
    {
        try {
            $district = District::find($district);
            if (!$district) {
                throw new Exception('District data not found');
            }
            return ApiResponseHelper::success('District data', $district);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get district data', $e->getMessage());
        }
    }

    public function update(Request $request, $district)
    {
        try {
            $this->districtService->update($district, $request->validated());
            return ApiResponseHelper::success('District data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating district data', $e->getMessage());
        }
    }

    public function destroy($district)
    {
        try {
            $district = District::find($district);
            if (!$district) {
                throw new Exception('District data not found');
            }

            $delete = $district->delete();
            if (!$delete) {
                throw new Exception('Failed to delete district data', 500);
            }
            return ApiResponseHelper::success('District data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('District data failed to delete', $e->getMessage());
        }
    }
}
