<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ReligionIndexRequest;
use App\Http\Requests\ReligionStoreRequest;
use App\Http\Requests\ReligionUpdateRequest;
use App\Models\Agama;
use App\Services\ReligionService;
use Exception;

class AgamaController extends Controller
{
    protected $religionService;

    public function __construct(ReligionService $religionService)
    {
        $this->religionService = $religionService;
    }

    public function index(ReligionIndexRequest $request)
    {
        try {
            $validated  = $request->validated();
            $religionQ  = Agama::query()->filter($validated);
            $religion   = isset($validated['paginate']) && $validated['paginate'] ? $religionQ->paginate($validated['perPage'] ?? 10) : $religionQ->get();
            return ApiResponseHelper::success('Religion data', $religion);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get religion data', $e->getMessage());
        }
    }

    public function store(ReligionStoreRequest $request)
    {
        try {
            $religion = $this->religionService->create($request->validated());
            return ApiResponseHelper::success('Religion data has been added successfully', $religion);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving religion data', $e->getMessage());
        }
    }

    public function show($religion)
    {
        try {
            $agama = Agama::find($religion);
            if (!$agama) {
                throw new Exception('Religion data not found');
            }
            return ApiResponseHelper::success('Religion detail', $agama);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get religion data', $e->getMessage());
        }
    }

    public function update(ReligionUpdateRequest $request, $religion)
    {
        try {
            $agama = Agama::find($religion);
            if (!$agama) {
                throw new Exception('Religion data not found');
            }
            $this->religionService->update($agama, $request->validated());
            return ApiResponseHelper::success('Religion data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating religion data', $e->getMessage());
        }
    }

    public function destroy($religion)
    {
        try {
            $agama = Agama::find($religion);
            if (!$agama) {
                throw new Exception('Religion data not found');
            }
            $delete = $agama->delete();
            if (!$delete) {
                throw new Exception('Failed to delete religion data', 500);
            }
            return ApiResponseHelper::success('Religion data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Religion data failed to delete', $e->getMessage());
        }
    }
}
