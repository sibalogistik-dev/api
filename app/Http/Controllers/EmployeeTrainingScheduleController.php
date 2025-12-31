<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeTrainingScheduleIndexRequest;
use App\Models\EmployeeTrainingSchedule;
use App\Services\EmployeeTrainingScheduleService;
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
        $validated          = $request->validated();
        $etsQ               = EmployeeTrainingSchedule::query()->filter($validated);
        $ets                = isset($validated['paginate']) && $validated['paginate'] ? $etsQ->paginate($validated['perPage'] ?? 10) : $etsQ->get();
        $transformedItems   = $ets instanceof LengthAwarePaginator ? $ets->getCollection() : $ets;
        $transformedEt      = $transformedItems->map(function ($item) {
            return [
                'id'                    => $item->id,
                'employee_id'           => $item->employee_training?->employee_id,
                'employee_name'         => $item->employeeTraining?->employee?->name,
                'training_type_id'      => $item->employee_training?->training_type_id,
                'training_type_name'    => $item->employeeTraining?->trainingType?->name,
                'schedule_time'         => $item->schedule_time,
                'title'                 => $item->title,
                'activity_description'  => $item->activity_description,
                'activity_result'       => $item->activity_result,
                'mentor_id'             => $item->mentor_id,
                'mentor_name'           => $item->mentor?->name,
                'mentor_notes'          => $item->mentor_notes,
                'mentor_assessment'     => $item->mentor_assessment,
            ];
        });

        if ($ets instanceof LengthAwarePaginator) {
            return ApiResponseHelper::success('Employee training schedule data', $ets->setCollection($transformedEt));
        }
        return ApiResponseHelper::success('Employee training schedule data', $transformedEt);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(EmployeeTrainingSchedule $employeeTrainingSchedule)
    {
        //
    }

    public function update(Request $request, EmployeeTrainingSchedule $employeeTrainingSchedule)
    {
        //
    }

    public function destroy(EmployeeTrainingSchedule $employeeTrainingSchedule)
    {
        //
    }
}
