<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ResignIndexRequest;
use App\Http\Requests\ResignStoreRequest;
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
            $itemsToTransform   = $resign instanceof LengthAwarePaginator ? $resign->getCollection() : $resign;
            $transformedResign  = $itemsToTransform->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'code'  => $item->code,
                ];
            });
            if ($resign instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Resigns data', $resign->setCollection($transformedResign));
            } else {
                return ApiResponseHelper::success('Resigns data', $transformedResign);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get resign data', $e->getMessage());
        }
    }

    public function store(ResignStoreRequest $request)
    {
        try {
            $resign = $this->resignService->create($request->validated());
            return ApiResponseHelper::success('Resign data has been added successfully', $resign);
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

    public function update(Request $request, $resign)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $e;
        }
    }

    public function destroy(Resign $resign)
    {
        try {
            //code...
        } catch (Exception $e) {
            //throw $e;
        }
    }
}
