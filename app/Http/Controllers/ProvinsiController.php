<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ProvinceIndexRequest;
use App\Http\Requests\ProvinceStoreRequest;
use App\Http\Requests\ProvinceUpdateRequest;
use App\Models\Province;
use App\Services\ProvinceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class ProvinsiController extends Controller
{
    protected $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function index(ProvinceIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $provinceQ              = Province::query()->filter($validated);
            $province               = isset($validated['paginate']) && $validated['paginate'] ? $provinceQ->paginate($validated['perPage'] ?? 10) : $provinceQ->get();
            $itemsToTransform       = $province instanceof LengthAwarePaginator ? $province->getCollection() : $province;
            $transformedProvince    = $itemsToTransform->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'code'  => $item->code,
                ];
            });
            if ($province instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Province data', $province->setCollection($transformedProvince));
            }
            return ApiResponseHelper::success('Province data', $transformedProvince);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get province data', $e->getMessage());
        }
    }

    public function store(ProvinceStoreRequest $request)
    {
        try {
            $province = $this->provinceService->create($request->validated());
            return ApiResponseHelper::success('Province data has been added successfully', $province);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving province data', $e->getMessage());
        }
    }

    public function show($province)
    {
        try {
            $province = Province::find($province);
            if (!$province) {
                throw new Exception('Province data not found');
            }
            return ApiResponseHelper::success('Province data', $province);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get province data', $e->getMessage());
        }
    }

    public function update(ProvinceUpdateRequest $request, $province)
    {
        try {
            $province = Province::find($province);
            if (!$province) {
                throw new Exception('Province data not found');
            }
            $this->provinceService->update($province, $request->validated());
            return ApiResponseHelper::success('Province data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating province data', $e->getMessage());
        }
    }

    public function destroy($province)
    {
        try {
            $province = Province::find($province);
            if (!$province) {
                throw new Exception('Province data not found');
            }
            $delete = $province->delete();
            if (!$delete) {
                throw new Exception('Failed to delete province data', 500);
            }
            return ApiResponseHelper::success('Province data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting employee data', $e->getMessage());
        }
    }
}
