<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\MarriageStatusIndexRequest;
use App\Http\Requests\MarriageStatusStoreRequest;
use App\Http\Requests\MarriageStatusUpdateRequest;
use App\Models\MarriageStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MarriageStatusController extends Controller
{
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
        //
    }

    public function show($marriageStatus)
    {
        $marriageStatus = MarriageStatus::find($marriageStatus);
        if (!$marriageStatus) {
            return ApiResponseHelper::error('Marriage status not found', [], 404);
        }
        $data = [
            'id'    => $marriageStatus->id,
            'name'  => $marriageStatus->name,
        ];
        return ApiResponseHelper::success('Marriage status data', $data);
    }

    public function update(MarriageStatusUpdateRequest $request, $marriageStatus)
    {
        //
    }

    public function destroy($marriageStatus)
    {
        //
    }
}
