<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\JobDescriptionIndexRequest;
use App\Http\Requests\JobDescriptionStoreRequest;
use App\Http\Requests\JobDescriptionUpdateRequest;
use App\Models\JobDescription;
use App\Services\JobDescriptionService;
use Exception;
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
        try {
            $validated              = $request->validated();
            $jdQ                    = JobDescription::query()->filter($validated);
            $jobDescs               = isset($validated['paginate']) && $validated['paginate'] ? $jdQ->paginate($validated['perPage'] ?? 10) : $jdQ->get();
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
            }
            return ApiResponseHelper::success('Job descriptions list', $transformedJobDescs);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get job description', $e->getMessage());
        }
    }

    public function store(JobDescriptionStoreRequest $request)
    {
        try {
            $job_desc = $this->jobDescriptionService->create($request->validated());
            return ApiResponseHelper::success('Job description data has been added successfully', $job_desc);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving job description data', $e->getMessage());
        }
    }
    public function show($jobDescription)
    {
        try {
            $job_desc = JobDescription::find($jobDescription);
            if (!$job_desc) {
                throw new Exception('Job description data not found');
            }
            return ApiResponseHelper::success('Job description detail', $job_desc);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get job desription', $e->getMessage());
        }
    }

    public function update(JobDescriptionUpdateRequest $request,  $jobDescription)
    {
        try {
            $job_desc = JobDescription::find($jobDescription);
            if (!$job_desc) {
                throw new Exception('Job description data not found');
            }
            $this->jobDescriptionService->update($job_desc, $request->validated());
            return ApiResponseHelper::success('Job description data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating job description data', $e->getMessage());
        }
    }

    public function destroy($jobDescription)
    {
        try {
            $job_desc = JobDescription::find($jobDescription);
            if (!$job_desc) {
                throw new Exception('Job description data not found');
            }
            $delete = $job_desc->delete();
            if (!$delete) {
                throw new Exception('Job description data failed to delete');
            }
            return ApiResponseHelper::success('Job description data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when deleting job description data', $e->getMessage());
        }
    }
}
