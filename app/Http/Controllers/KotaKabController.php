<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\CityStoreRequest;
use App\Models\City;
use App\Services\CityService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KotaKabController extends Controller
{
    protected $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index(Request $request)
    {
        try {
            $validated = $request->validated();
            $cityQuery = City::query()->filter($validated);
            $city = isset($validated['paginate']) && $validated['paginate'] ? $cityQuery->paginate($validated['paginate'] ?? 10) : $cityQuery->get();
            $itemToTransform = $city instanceof LengthAwarePaginator ? $city->getCollection() : $city;
            $tranformedCity = $itemToTransform->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'code'  => $item->code,
                ];
            });
            if ($city instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('City list', $city->setCollection($tranformedCity));
            } else {
                return ApiResponseHelper::success('City list', $tranformedCity);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get city data', $e->getMessage());
        }
    }

    public function store(CityStoreRequest $request)
    {
        try {
            $city = $this->cityService->create($request->validated());
            return ApiResponseHelper::success('City data has been added successfully', $city);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving city data', $e->getMessage(), 500);
        }
    }

    public function show($city)
    {
        try {
            $city = City::find($city);
            if (!$city) {
                throw new Exception('City not found');
            }
            return ApiResponseHelper::success('City data', $city);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get city data', $e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, $city)
    {
        try {
            $city = City::find($city);
            if (!$city) {
                throw new Exception('City not found');
            }
            $this->cityService->update($city, $request->validated());
            return ApiResponseHelper::success('City data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating city data', $e->getMessage(), $e->getCode());
        }
    }

    public function destroy($city)
    {
        try {
            $city = City::find($city);
            if (!$city) {
                throw new Exception('City not found');
            }
            $delete = $city->delete();
            if (!$delete) {
                throw new Exception('Failed to delete city data', 500);
            }
            return ApiResponseHelper::success('City data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('City data failed to delete', $e->getMessage(), $e->getCode());
        }
    }
}
