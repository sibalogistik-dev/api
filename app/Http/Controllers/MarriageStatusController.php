<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\MarriageStatusIndexRequest;
use App\Http\Requests\MarriageStatusStoreRequest;
use App\Http\Requests\MarriageStatusUpdateRequest;
use App\Models\MarriageStatus;
use App\Services\MarriageStatusService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class MarriageStatusController extends Controller
{
    protected $marriageStatusService;

    public function __construct(MarriageStatusService $marriageStatusService)
    {
        $this->marriageStatusService = $marriageStatusService;
    }

    public function index(MarriageStatusIndexRequest $request)
    {
        try {
            $validated                  = $request->validated();
            $msQuery                    = MarriageStatus::query()->filter($validated);
            $marriageStatus             = isset($validated['paginate']) && $validated['paginate'] ? $msQuery->paginate($validated['perPage'] ?? 10) : $msQuery->get();
            $itemsToTransform           = $marriageStatus instanceof LengthAwarePaginator ? $marriageStatus->getCollection() : $marriageStatus;
            $transformedMarriageStatus  = $itemsToTransform->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                ];
            });
            if ($marriageStatus instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Marriage status list', $marriageStatus->setCollection($transformedMarriageStatus));
            } else {
                return ApiResponseHelper::success('Marriage status list', $transformedMarriageStatus);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get marriage status data', $e->getMessage());
        }
    }

    public function store(MarriageStatusStoreRequest $request)
    {
        try {
            $marriageStatus = $this->marriageStatusService->create($request->validated());
            return ApiResponseHelper::success('Marriage status data has been added successfully', $marriageStatus);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving marriage status data', $e->getMessage(), 500);
        }
    }

    public function show($marriageStatus)
    {
        try {
            $marriageStatus = MarriageStatus::find($marriageStatus);
            if (!$marriageStatus) {
                throw new Exception('Marriage status not found');
            }
            return ApiResponseHelper::success('Marriage status data', $marriageStatus);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get marriage status data', $e->getMessage());
        }
    }

    public function update(MarriageStatusUpdateRequest $request, $marriageStatus)
    {
        try {
            $marriageStatus = MarriageStatus::find($marriageStatus);
            if (!$marriageStatus) {
                throw new Exception('Marriage status not found');
            }
            $this->marriageStatusService->update($marriageStatus, $request->validated());
            return ApiResponseHelper::success('Marriage status data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating marriage status data', $e->getMessage(), 500);
        }
    }

    public function destroy($marriageStatus)
    {
        try {
            $marriageStatus = MarriageStatus::find($marriageStatus);
            if (!$marriageStatus) {
                throw new Exception('Marriage status not found');
            }

            $delete = $marriageStatus->delete();
            if (!$delete) {
                throw new Exception('Failed to delete marriage status data');
            }
            return ApiResponseHelper::success('Marriage status data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Marriage status data failed to delete', $e->getMessage());
        }
    }
}
