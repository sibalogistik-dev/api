<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\HolidayIndexRequest;
use App\Http\Requests\HolidayStoreRequest;
use App\Http\Requests\HolidayUpdateRequest;
use App\Models\Holiday;
use App\Services\HolidayService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class HolidayController extends Controller
{
    protected $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index(HolidayIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $holidayQ           = Holiday::query()->filter($validated);
            $holiday            = isset($validated['paginate']) && $validated['paginate'] ? $holidayQ->paginate($validated['perPage'] ?? 10) : $holidayQ->get();
            $transformedItems   = $holiday instanceof LengthAwarePaginator ? $holiday->getCollection() : $holiday;
            $transformedHoliday = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'date'          => $item->date,
                ];
            });
            if ($holiday instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Holiday data', $holiday->setCollection($transformedHoliday));
            } else {
                return ApiResponseHelper::success('Holiday data', $transformedHoliday);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get holiday data', $e->getMessage());
        }
    }

    public function store(HolidayStoreRequest $request)
    {
        try {
            $holiday = $this->holidayService->create($request->validated());
            return ApiResponseHelper::success('Holiday data has been added successfully', $holiday);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving holiday data', $e->getMessage());
        }
    }
    public function show($holiday)
    {
        try {
            $holiday = Holiday::find($holiday);
            if (!$holiday) {
                return ApiResponseHelper::error('Holiday data not found', null, 404);
            }
            return ApiResponseHelper::success('Holiday data', $holiday);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get holiday data', $e->getMessage());
        }
    }

    public function update(HolidayUpdateRequest $request, Holiday $holiday)
    {
        try {
            $holiday = $this->holidayService->update($holiday, $request->validated());
            return ApiResponseHelper::success('Holiday data has been updated successfully', $holiday);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating holiday data', $e->getMessage());
        }
    }

    public function destroy($holiday)
    {
        try {
            $holiday = Holiday::find($holiday);
            if (!$holiday) {
                return ApiResponseHelper::error('Holiday data not found', null, 404);
            }
            $delete = $holiday->delete();
            if (!$delete) {
                return ApiResponseHelper::error('Failed to delete holiday data', null, 500);
            }
            return ApiResponseHelper::success('Holiday data has been deleted successfully', null);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting holiday data', $e->getMessage());
        }
    }
}
