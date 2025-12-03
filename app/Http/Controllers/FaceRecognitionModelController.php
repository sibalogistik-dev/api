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
    // public function store(Request $request)
    // {
    //     try {
    //         $validate = Validator::make($request->all(), [
    //             'employee_id'   => ['required', 'integer', 'exists:karyawans,id'],
    //             'image_path'    => ['required', new Base64Image(['jpeg', 'jpg', 'png', 'webp'], 4 * 1024 * 1024)],
    //         ]);

    //         if ($validate->fails()) {
    //             throw new Exception($validate->errors());
    //         }
    //         // return ApiResponseHelper::success('Face recognition data has been added successfully', $faceRecognition);
    //     } catch (Exception $e) {
    //         return ApiResponseHelper::error('Error when saving face recognition data', $e->getMessage());
    //     }
    // }

    public function show($faceRecognition)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function update(Request $request, $faceRecognition)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }

    public function destroy($faceRecognition)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }
}
