<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\VillageIndexRequest;
use App\Http\Requests\VillageStoreRequest;
use App\Http\Requests\VillageUpdateRequest;
use App\Models\Village;
use App\Services\VillageService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class KelurahanController extends Controller
{
    protected $villageService;

    public function __construct(VillageService $villageService)
    {
        $this->villageService = $villageService;
    }

    public function index(VillageIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $villageQ           = Village::query()->filter($validated);
            $village            = isset($validated['paginate']) && $validated['paginate'] ? $villageQ->paginate($validated['perPage'] ?? 10) : $villageQ->get();
            $transformedItems   = $village instanceof LengthAwarePaginator ? $village->getCollection() : $village;
            $transformedVillage = $transformedItems->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'code'  => $item->code,
                ];
            });
            if ($village instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Village list', $village->setCollection($transformedVillage));
            } else {
                return ApiResponseHelper::success('Village list', $transformedVillage);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get village data', $e->getMessage());
        }
    }

    public function store(VillageStoreRequest $request)
    {
        try {
            $village = $this->villageService->create($request->validated());
            return ApiResponseHelper::success('Village data has been added successfully', $village);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving village data', $e->getMessage());
        }
    }

    public function show($village)
    {
        try {
            $village = Village::find($village);
            if (!$village) {
                throw new Exception('Village data not found');
            }
            return ApiResponseHelper::success('Village data', $village);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get village data', $e->getMessage());
        }
    }

    public function update(VillageUpdateRequest $request, $village)
    {
        try {
            $this->villageService->update($village, $request->validated());
            return ApiResponseHelper::success('Village data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating village data', $e->getMessage());
        }
    }

    public function destroy($village)
    {
        try {
            $village = Village::find($village);
            if (!$village) {
                throw new Exception('Village data not found');
            }

            $delete = $village->delete();
            if (!$delete) {
                throw new Exception('Failed to delete village data');
            }
            return ApiResponseHelper::success('Village data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Village data failed to delete', $e->getMessage());
        }
    }
}
