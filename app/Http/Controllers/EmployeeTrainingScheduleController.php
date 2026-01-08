<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeTrainingScheduleIndexRequest;
use App\Http\Requests\EmployeeTrainingScheduleStoreRequest;
use App\Http\Requests\EmployeeTrainingScheduleUpdateRequest;
use App\Models\EmployeeTrainingSchedule;
use App\Services\EmployeeTrainingScheduleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeTrainingScheduleController extends Controller
{
    private $employeeTrainingScheduleService;

    public function __construct(EmployeeTrainingScheduleService $employeeTrainingScheduleService)
    {
        $this->employeeTrainingScheduleService = $employeeTrainingScheduleService;
    }

    public function index(EmployeeTrainingScheduleIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $etsQ               = EmployeeTrainingSchedule::query()->filter($validated);
            $ets                = isset($validated['paginate']) && $validated['paginate'] ? $etsQ->paginate($validated['perPage'] ?? 10) : $etsQ->get();
            $transformedItems   = $ets instanceof LengthAwarePaginator ? $ets->getCollection() : $ets;
            $transformedEt      = $transformedItems->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'employee_id'           => $item->employeeTraining?->employee_id,
                    'trainee_name'          => $item->employeeTraining?->employee?->name,
                    'mentor_id'             => $item->mentor_id,
                    'mentor_name'           => $item->mentor?->name,
                    'training_type_id'      => $item->employeeTraining?->training_type_id,
                    'training_type_name'    => $item->employeeTraining?->trainingType?->name,
                    'schedule_time'         => $item->schedule_time,
                    'title'                 => $item->title,
                    'activity_description'  => $item->activity_description,
                    'activity_result'       => $item->activity_result,
                    'mentor_notes'          => $item->mentor_notes,
                    'mentor_assessment'     => $item->mentor_assessment,
                ];
            });

            if ($ets instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee training schedule data', $ets->setCollection($transformedEt));
            }
            return ApiResponseHelper::success('Employee training schedule data', $transformedEt);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee training schedule data', $e->getMessage());
        }
    }

    public function store(EmployeeTrainingScheduleStoreRequest $request)
    {
        try {
            $employeeTrainingSchedule = $this->employeeTrainingScheduleService->create($request->validated());
            return ApiResponseHelper::success('Employee training schedule data has been added successfully', $employeeTrainingSchedule);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee training schedule data', $e->getMessage());
        }
    }

    public function show($employeeTrainingSchedule)
    {
        try {
            $trainingSchedule = EmployeeTrainingSchedule::find($employeeTrainingSchedule);
            if (!$trainingSchedule) {
                throw new Exception('Employee training schedule data not found');
            }
            $data = [
                'id'                    => $trainingSchedule->id,
                'employee_id'           => $trainingSchedule->employeeTraining?->employee_id,
                'trainee_name'          => $trainingSchedule->employeeTraining?->employee?->name,
                'mentor_id'             => $trainingSchedule->mentor_id,
                'mentor_name'           => $trainingSchedule->mentor?->name,
                'training_type_id'      => $trainingSchedule->employeeTraining?->training_type_id,
                'training_type_name'    => $trainingSchedule->employeeTraining?->trainingType?->name,
                'schedule_time'         => $trainingSchedule->schedule_time,
                'title'                 => $trainingSchedule->title,
                'activity_description'  => $trainingSchedule->activity_description,
                'activity_result'       => $trainingSchedule->activity_result,
                'mentor_notes'          => $trainingSchedule->mentor_notes,
                'mentor_assessment'     => $trainingSchedule->mentor_assessment,
            ];
            return ApiResponseHelper::success("Employee training schedule's detail", $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get employee training schedule data", $e->getMessage());
        }
    }

    public function update(EmployeeTrainingScheduleUpdateRequest $request, $employeeTrainingSchedule)
    {
        try {
            $ets = EmployeeTrainingSchedule::find($employeeTrainingSchedule);
            if (!$ets) {
                throw new Exception('Employee training schedule data not found');
            }
            $this->employeeTrainingScheduleService->update($ets, $request->validated());
            return ApiResponseHelper::success('Employee\'s training schedule data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee\'s training schedule data', $e->getMessage());
        }
    }

    public function destroy($employeeTrainingSchedule)
    {
        try {
            $ets = EmployeeTrainingSchedule::find($employeeTrainingSchedule);
            if (!$ets) {
                throw new Exception('Employee\'s training schedule data not found');
            }
            $delete = $ets->delete();
            if (!$delete) {
                throw new Exception('Failed to delete employee\'s training schedule data');
            }
            return ApiResponseHelper::success('Employee\'s training schedule data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Employee\'s training schedule data failed to delete', $e->getMessage());
        }
    }
}
