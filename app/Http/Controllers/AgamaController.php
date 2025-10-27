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
        $validated = $request->validated();
        $religionQuery = Agama::query()->filter($validated)->orderBy('id');
        $religion = isset($validated['paginate']) && $validated['paginate'] ? $religionQuery->paginate($validated['perPage'] ?? 10) : $religionQuery->get();
        return ApiResponseHelper::success('Religion list', $religion);
    }

    public function store(ReligionStoreRequest $request)
    {
        try {
            $religion = $this->religionService->create($request->validated());
            return ApiResponseHelper::success('Religion data has been added successfully', $religion);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving religion data', $e->getMessage(), 500);
        }
    }

    public function show($religion)
    {
        $agama = Agama::find($religion);
        if (!$agama) {
            return ApiResponseHelper::error('Religion not found', [], 404);
        }
        return ApiResponseHelper::success('Religion detail', $agama);
    }

    public function update(ReligionUpdateRequest $request, $religion)
    {
        try {
            $agama = Agama::find($religion);
            if (!$agama) {
                throw new Exception('Religion not found');
            }
            $this->religionService->update($agama, $request->validated());
            return ApiResponseHelper::success('Religion data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating religion data', $e->getMessage(), 500);
        }
    }

    public function destroy($religion)
    {
        $agama = Agama::find($religion);
        if (!$agama) {
            return ApiResponseHelper::error('Religion not found', [], 404);
        }
        $delete = $agama->delete();
        if (!$delete) {
            return ApiResponseHelper::error('Religion data failed to delete', null, 500);
        }
        return ApiResponseHelper::success('Religion data has been deleted successfully');
    }
}
