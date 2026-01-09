<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\EmployeeTrainingDocumentRequest;
use App\Http\Requests\EmployeeTrainingIndexRequest;
use App\Http\Requests\EmployeeTrainingReportRequest;
use App\Http\Requests\EmployeeTrainingStoreRequest;
use App\Http\Requests\EmployeeTrainingUpdateRequest;
use App\Models\EmployeeTraining;
use App\Services\EmployeeTrainingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeTrainingController extends Controller
{
    protected $employeeTrainingService;

    public function __construct(EmployeeTrainingService $employeeTrainingService)
    {
        $this->employeeTrainingService = $employeeTrainingService;
    }

    public function index(EmployeeTrainingIndexRequest $request)
    {
        try {
            $validated          = $request->validated();
            $etQ                = EmployeeTraining::query()->filter($validated);
            $et                 = isset($validated['paginate']) && $validated['paginate'] ? $etQ->paginate($validated['perPage'] ?? 10) : $etQ->get();
            $transformedItems   = $et instanceof LengthAwarePaginator ? $et->getCollection() : $et;
            $transformedEt      = $transformedItems->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'employee_id'           => $item->employee_id,
                    'employee_name'         => $item->employee?->name,
                    'training_type_id'      => $item->training_type_id,
                    'training_type_name'    => $item->trainingType?->name,
                    'start_date'            => $item->start_date,
                    'notes'                 => $item->notes,
                    'status'                => $item->status,
                ];
            });

            if ($et instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee training data', $et->setCollection($transformedEt));
            }
            return ApiResponseHelper::success('Employee training data', $transformedEt);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get employee training data', $e->getMessage());
        }
    }

    public function store(EmployeeTrainingStoreRequest $request)
    {
        try {
            $employeeTraining = $this->employeeTrainingService->create($request->validated());
            return ApiResponseHelper::success('Employee training data has been added successfully', $employeeTraining);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving employee training data', $e->getMessage());
        }
    }

    public function show($employeeTraining)
    {
        try {
            $training = EmployeeTraining::find($employeeTraining);
            if (!$training) {
                throw new Exception('Employee training data not found');
            }
            $data = [
                'id'                    => $training->id,
                'employee_id'           => $training->employee_id,
                'employee_name'         => $training->employee?->name,
                'training_type_id'      => $training->training_type_id,
                'training_type_name'    => $training->trainingType?->name,
                'start_date'            => $training->start_date,
                'notes'                 => $training->notes,
                'status'                => $training->status,
            ];
            return ApiResponseHelper::success("Employee training's detail", $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get employee training data", $e->getMessage());
        }
    }

    public function update(EmployeeTrainingUpdateRequest $request, $employeeTraining)
    {
        try {
            $et = EmployeeTraining::find($employeeTraining);
            if (!$et) {
                throw new Exception('Employee training data not found');
            }
            $this->employeeTrainingService->update($et, $request->validated());
            return ApiResponseHelper::success('Employee training data has been updated successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when updating employee training data', $e->getMessage());
        }
    }

    public function destroy($employeeTraining)
    {
        try {
            $et = EmployeeTraining::find($employeeTraining);
            if (!$et) {
                throw new Exception('Employee training data not found');
            }
            $delete = $et->delete();
            if (!$delete) {
                throw new Exception('Failed to delete employee training data');
            }
            return ApiResponseHelper::success('Employee training data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Employee training data failed to delete', $e->getMessage());
        }
    }

    public function report(EmployeeTrainingReportRequest $request)
    {
        try {
            $validated      = $request->validated();
            $report         = $this->employeeTrainingService->report($validated);
            $start          = $validated['start_date'];
            $end            = $validated['end_date'];
            $pdf            = Pdf::loadView('employee-training.report', compact('report', 'start', 'end'))->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan Pelatihan Karyawan.pdf');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when generating employee training report', $e->getMessage());
        }
    }

    public function document(EmployeeTrainingDocumentRequest $request)
    {
        try {
            //code...
        } catch (Exception $e) {
            //code...
        }
    }
}
