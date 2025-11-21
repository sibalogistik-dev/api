<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\JobTitleIndexRequest;
use App\Http\Requests\JobTitleStoreRequest;
use App\Http\Requests\JobTitleUpdateRequest;
use App\Models\Jabatan;
use App\Services\JobTitleService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class JabatanController extends Controller
{
    protected $jobTitleService;

    public function __construct(JobTitleService $jobTitleService)
    {
        $this->jobTitleService = $jobTitleService;
    }

    public function index(JobTitleIndexRequest $request)
    {
        $validated          = $request->validated();
        $jabatanQuery       = Jabatan::query()->filter($validated);
        $jabatan            = isset($validated['paginate']) && $validated['paginate'] ? $jabatanQuery->paginate($validated['perPage'] ?? 10) : $jabatanQuery->get();
        $itemsToTransform   = $jabatan instanceof LengthAwarePaginator ? $jabatan->getCollection() : $jabatan;
        $transformedJabatan = $itemsToTransform->map(function ($item) {
            return [
                'id'            => $item->id,
                'name'          => $item->name,
                'description'   => $item->description,
            ];
        });
        if ($jabatan instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Job titles list', $jabatan->setCollection($transformedJabatan));
        } else {
            return ApiResponseHelper::success('Job titles list', $transformedJabatan);
        }
    }

    public function store(JobTitleStoreRequest $request)
    {
        try {
            $job_title = $this->jobTitleService->create($request->validated());
            return ApiResponseHelper::success('Job title data has been added successfully', $job_title);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving job title data', $e->getMessage(), 500);
        }
    }

    public function show($job_title)
    {
        $job_title = Jabatan::find($job_title);
        if (!$job_title) {
            return ApiResponseHelper::error('Job title not found', []);
        }
        return ApiResponseHelper::success('Job title detail', $job_title);
    }

    public function update(JobTitleUpdateRequest $request, $job_title)
    {
        try {
            $job_title = Jabatan::find($job_title);
            if (!$job_title) {
                throw new Exception('Job title not found');
            }
            $this->jobTitleService->update($job_title, $request->validated());
            return ApiResponseHelper::success('Job title data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating job titke data', $e->getMessage(), 500);
        }
    }

    public function destroy($job_title)
    {
        $job_title = Jabatan::find($job_title);
        if (!$job_title) {
            return ApiResponseHelper::error('Job title not found', []);
        }
        $delete = $job_title->delete();
        if ($delete) {
            return ApiResponseHelper::success('Job title data has been deleted successfully');
        } else {
            return ApiResponseHelper::error('Job title data failed to delete', null, 500);
        }
    }
}
