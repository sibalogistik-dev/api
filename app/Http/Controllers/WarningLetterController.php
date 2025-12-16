<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\WarningLetterDocumentRequest;
use App\Http\Requests\WarningLetterIndexRequest;
use App\Http\Requests\WarningLetterReportRequest;
use App\Http\Requests\WarningLetterStoreRequest;
use App\Http\Requests\WarningLetterUpdateRequest;
use App\Models\WarningLetter;
use App\Services\WarningLetterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class WarningLetterController extends Controller
{
    protected $warningLetterService;

    public function __construct(WarningLetterService $warningLetterService)
    {
        $this->warningLetterService = $warningLetterService;
    }

    public function index(WarningLetterIndexRequest $request)
    {
        try {
            $validated                  = $request->validated();
            $warningLetterQ             = WarningLetter::query()->filter($validated);
            $warningLetters             = isset($validated['paginate']) && $validated['paginate'] ? $warningLetterQ->paginate($validated['perPage'] ?? 10) : $warningLetterQ->get();
            $transformedItems           = $warningLetters instanceof LengthAwarePaginator ? $warningLetters->getCollection() : $warningLetters;
            $transformedWarningLetters  = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'employee_id'   => $item->employee_id,
                    'employee_name' => $item->employee->name,
                    'letter_number' => $item->letter_number,
                    'letter_date'   => $item->letter_date,
                    'reason'        => $item->reason,
                    'issuer_id'     => $item->issued_by,
                    'issuer_name'   => $item->issuer->name,
                    'notes'         => $item->notes,
                ];
            });
            if ($warningLetters instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Warning letter data', $warningLetters->setCollection($transformedWarningLetters));
            }
            return ApiResponseHelper::success('Warning letter data', $transformedWarningLetters);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get warning letter data', $e->getMessage());
        }
    }

    public function store(WarningLetterStoreRequest $request)
    {
        try {
            $warningLetter = $this->warningLetterService->create($request->validated());
            return ApiResponseHelper::success('Warning letter data', $warningLetter);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get warning letter data', $e->getMessage());
        }
    }

    public function show($warningLetter)
    {
        try {
            $warningLetter = WarningLetter::find($warningLetter);
            if (!$warningLetter) {
                throw new Exception('Warning letter data not found');
            }
            $data = [
                'id'            => $warningLetter->id,
                'employee_id'   => $warningLetter->employee_id,
                'employee_name' => $warningLetter->employee->name,
                'letter_number' => $warningLetter->letter_number,
                'letter_date'   => $warningLetter->letter_date,
                'reason'        => $warningLetter->reason,
                'issued_by'     => $warningLetter->issuer->name,
                'notes'         => $warningLetter->notes,
            ];

            return ApiResponseHelper::success('Warning letter data', $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get warning letter data', $e->getMessage());
        }
    }

    public function update(WarningLetterUpdateRequest $request, $warningLetter)
    {
        try {
            $warningLetter = WarningLetter::find($warningLetter);
            if (!$warningLetter) {
                throw new Exception('Warning letter data not found');
            }
            $warningLetter = $this->warningLetterService->update($warningLetter, $request->validated());
            return ApiResponseHelper::success('Warning letter data has been updated successfully', $warningLetter);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to update warning letter data', $e->getMessage());
        }
    }

    public function destroy($warningLetter)
    {
        try {
            $warningLetter = WarningLetter::find($warningLetter);
            if (!$warningLetter) {
                throw new Exception('Warning letter data not found');
            }
            $delete = $warningLetter->delete();
            if (!$delete) {
                throw new Exception('Failed to delete warning letter data');
            }
            return ApiResponseHelper::success('Warning letter data has been deleted successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to delete warning letter data', $e->getMessage());
        }
    }

    public function document(WarningLetterDocumentRequest $request)
    {
        try {
            $validated  = $request->validated();
            $document   = $this->warningLetterService->document($validated);
            $pdf        = Pdf::loadView('warning-letter.document', compact('document'))->setPaper('a4');
            return $pdf->stream('Surat Peringatan Karyawan.pdf');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when warning letter document', $e->getMessage());
        }
    }

    public function report(WarningLetterReportRequest $request)
    {
        try {
            $validated  = $request->validated();
            $report     = $this->warningLetterService->report($validated);
            $start      = $validated['start_date'] ?? null;
            $end        = $validated['end_date'] ?? null;
            $pdf        = Pdf::loadView('warning-letter.report', compact('report', 'start', 'end'))->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan SP Karyawan.pdf');
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when warning letter report', $e->getMessage());
        }
    }
}
