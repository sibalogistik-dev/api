<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EducationIndexRequest;
use App\Http\Requests\EducationStoreRequest;
use App\Http\Requests\EducationUpdateRequest;
use App\Models\Pendidikan;
use App\Services\EducationService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    protected $educationService;

    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
    }

    public function index(EducationIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $educationQ             = Pendidikan::query()->filter($validated);
            $education              = isset($validated['paginate']) && $validated['paginate'] ? $educationQ->paginate($validated['perPage'] ?? 10) : $educationQ->get();
            $itemsToTransform       = $education instanceof LengthAwarePaginator ? $education->getCollection() : $education;
            $transformedEducation   = $itemsToTransform->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                ];
            });
            if ($education instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Pendidikan list', $education->setCollection($transformedEducation));
            }
            return ApiResponseHelper::success('Pendidikan list', $transformedEducation);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get education data', $e->getMessage());
        }
    }

    public function store(EducationStoreRequest $request)
    {
        try {
            $education = $this->educationService->create($request->validated());
            return ApiResponseHelper::success('Education data has been added successfully', $education);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving education data', $e->getMessage());
        }
    }

    public function show($education)
    {
        $education = Pendidikan::find($education);
        if (!$education) {
            return ApiResponseHelper::error('Education data not found', []);
        }
        $data = [
            'id'    => $education->id,
            'name'  => $education->name,
        ];
        return ApiResponseHelper::success("Education's detail", $data);
    }

    public function update(EducationUpdateRequest $request, Pendidikan $education)
    {
        try {
            $this->educationService->update($education, $request->validated());
            return ApiResponseHelper::success('Education data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating education data', $e->getMessage());
        }
    }

    public function destroy($education)
    {

        try {
            $education  = Pendidikan::find($education);
            if (!$education) {
                throw new Exception('Education data not found');
            }
            $delete     = $education->delete();
            if (!$delete) {
                throw new Exception('Education data failed to delete', 500);
            }
            return ApiResponseHelper::success('Education data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Education data failed to delete', $e->getMessage());
        }
    }
}
