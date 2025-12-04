<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\FaceRecognitionIndexRequest;
use App\Http\Requests\FaceRecognitionStoreRequest;
use App\Models\FaceRecognitionModel;
use App\Rules\Base64Image;
use App\Services\FaceRecognitionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class FaceRecognitionModelController extends Controller
{
    protected $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

    public function index(FaceRecognitionIndexRequest $request)
    {
        try {
            $validated                  = $request->validated();
            $faceRecognitionQ           = FaceRecognitionModel::query()->filter($validated);
            $faceRecognition            = isset($validated['paginate']) && $validated['paginate'] ? $faceRecognitionQ->paginate($validated['perPage'] ?? 10) : $faceRecognitionQ->get();
            $transformedItems           = $faceRecognition instanceof LengthAwarePaginator ? $faceRecognition->getCollection() : $faceRecognition;
            $transformedFaceRecognition = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'employee_id'   => $item->employee_id,
                    'employee_name' => $item->employee->name,
                    'image_path'    => $item->image_path,
                ];
            });
            if ($faceRecognition instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Face recognition data', $faceRecognition->setCollection($transformedFaceRecognition));
            } else {
                return ApiResponseHelper::success('Face recognition data', $transformedFaceRecognition);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get face recognition data', $e->getMessage());
        }
    }

    public function store(FaceRecognitionStoreRequest $request)
    {
        try {
            $faceRecognition = $this->faceRecognitionService->create($request->validated());
            return ApiResponseHelper::success('Face recognition data has been added successfully', $faceRecognition);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving face recognition data', $e->getMessage());
        }
    }

    public function show($faceRecognition)
    {
        try {
            $query = FaceRecognitionModel::find($faceRecognition);
            if (!$query) {
                throw new Exception('Face recognition data not found');
            }
            $data = [
                'id'            => $query->id,
                'employee_id'   => $query->employee_id,
                'employee_name' => $query->employee->name,
                'image_path'    => $query->image_path,
            ];
            return ApiResponseHelper::success('Face recognition detail', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to retrieve face recognition detail', $e->getMessage());
        }
    }

    public function update(Request $request, $faceRecognition)
    {
        return ApiResponseHelper::success('This endpoint does not have any functionality', []);
    }

    public function destroy($faceRecognition)
    {
        try {
            $query = FaceRecognitionModel::find($faceRecognition);
            if (!$query) {
                throw new Exception('Face recognition data not found');
            }
            $query->delete();
            if (file_exists(storage_path('app/public/' . $query->image_path))) {
                unlink(storage_path('app/public/' . $query->image_path));
            }
            return ApiResponseHelper::success('Face recognition data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to delete face recognition data', $e->getMessage());
        }
    }
}
