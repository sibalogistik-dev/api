<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\JobDescriptionIndexRequest;
use App\Http\Requests\JobDescriptionStoreRequest;
use App\Http\Requests\JobDescriptionUpdateRequest;
use App\Models\JobDescription;
use App\Services\JobDescriptionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class JobDescriptionController extends Controller
{
    protected $jobDescriptionService;

    public function __construct(JobDescriptionService $jobDescriptionService)
    {
        $this->jobDescriptionService = $jobDescriptionService;
    }

    public function index(JobDescriptionIndexRequest $request)
    {
        $validated              = $request->validated();
        $jdQuery                = JobDescription::query()->filter($validated);
        $jobDescs               = isset($validated['paginate']) && $validated['paginate'] ? $jdQuery->paginate($validated['perPage'] ?? 10) : $jdQuery->get();
        $itemsToTransform       = $jobDescs instanceof LengthAwarePaginator ? $jobDescs->getCollection() : $jobDescs;
        $transformedJobDescs    = $itemsToTransform->map(function ($item) {
            return [
                'id'                => $item->id,
                'job_title'         => $item->jobTitle->name,
                'task_name'         => $item->task_name,
                'task_detail'       => $item->task_detail,
                'priority_level'    => $item->priority_level,
            ];
        });
        if ($jobDescs instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Job descriptions list', $jobDescs->setCollection($transformedJobDescs));
        } else {
            return ApiResponseHelper::success('Job descriptions list', $transformedJobDescs);
        }
    }

    public function store(JobDescriptionStoreRequest $request)
    {
        try {
            $job_desc = $this->jobDescriptionService->create($request->validated());
            return ApiResponseHelper::success('Job description data has been added successfully', $job_desc);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving job description data', $e->getMessage(), 500);
        }
    }
    public function show(JobDescription $jobDescription)
    {
        $job_desc = JobDescription::find($jobDescription->id);
        if (!$job_desc) {
            return ApiResponseHelper::error('Job description not found', [], 404);
        }
        return ApiResponseHelper::success('Job description detail', $job_desc);
    }

    public function update(JobDescriptionUpdateRequest $request,  $jobDescription)
    {
        try {
            $job_desc = JobDescription::find($jobDescription);
            if (!$job_desc) {
                throw new Exception('Job description not found');
            }
            $this->jobDescriptionService->update($job_desc, $request->validated());
            return ApiResponseHelper::success('Job description data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating job description data', $e->getMessage(), 500);
        }
    }

    public function destroy($jobDescription)
    {
        $job_desc = JobDescription::find($jobDescription);
        if (!$job_desc) {
            return ApiResponseHelper::error('Job description not found', [], 404);
        }
        $delete = $job_desc->delete();
        if ($delete) {
            return ApiResponseHelper::success('Job description data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Job description data failed to delete', null, 500);
        }
    }
}
