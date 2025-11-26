<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ResignIndexRequest;
use App\Http\Requests\ResignStoreRequest;
use App\Http\Requests\ResignUpdateRequest;
use App\Models\Resign;
use App\Services\ResignService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ResignController extends Controller
{
    protected $resignService;

    public function __construct(ResignService $resignService)
    {
        $this->resignService = $resignService;
    }

    public function index(ResignIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $resignQ            = Resign::query()->filter($validated);
            $resign             = isset($validated['paginate']) && $validated['paginate'] ? $resignQ->paginate($validated['perPage'] ?? 10) : $resignQ->get();
            $transformedItems   = $resign instanceof LengthAwarePaginator ? $resign->getCollection() : $resign;
            $transformedResign  = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'employee_id'   => $item->employee_id,
                    'employee_name' => $item->employee->name,
                    'date'          => $item->date,
                    'status'        => $item->status,
                    'description'   => $item->description,
                ];
            });
            if ($resign instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Resigns data', $resign->setCollection($transformedResign));
            }
            return ApiResponseHelper::success('Resigns data', $transformedResign);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get resign data', $e->getMessage());
        }
    }

    public function store(ResignStoreRequest $request)
    {
        try {
            $resign = $this->resignService->create($request->validated());
            if ($resign) {
                return ApiResponseHelper::success('Resign data has been added successfully', $resign);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving resign data', $e->getMessage());
        }
    }

    public function show($resign)
    {
        try {
            $resign = Resign::find($resign);
            if (!$resign) {
                throw new Exception('Resign data not found');
            }
            return ApiResponseHelper::success('Resign data', $resign);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get resign data', $e->getMessage());
        }
    }

    public function update(ResignUpdateRequest $request, $resign)
    {
        try {
            $resign = Resign::find($resign);
            if (!$resign) {
                throw new Exception('Resign data not found');
            }
            $this->resignService->update($resign, $request->validated());
            return ApiResponseHelper::success('Resign data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating village data', $e->getMessage());
        }
    }

    public function destroy($resign)
    {
        try {
            $resign = Resign::find($resign);
            if (!$resign) {
                throw new Exception('Resign data not found');
            }

            $delete = $resign->delete();
            if (!$delete) {
                throw new Exception('Failed to delete village data');
            }
            return ApiResponseHelper::success('Resign data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Resign data failed to delete', $e->getMessage());
        }
    }
}
