<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\ReprimandLetterIndexRequest;
use App\Http\Requests\ReprimandLetterStoreRequest;
use App\Models\ReprimandLetter;
use App\Services\ReprimandLetterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReprimandLetterController extends Controller
{
    protected ReprimandLetterService $reprimandLetterService;

    public function __construct(ReprimandLetterService $reprimandLetterService)
    {
        $this->reprimandLetterService = $reprimandLetterService;
    }

    public function index(ReprimandLetterIndexRequest $request)
    {
        try {
            $validated                  = $request->validated();
            $reprimandLetterQ           = ReprimandLetter::query()->filter($validated);
            $reprimandLetter            = isset($validated['paginate']) && $validated['paginate'] ? $reprimandLetterQ->paginate($validated['perPage'] ?? 10) : $reprimandLetterQ->get();
            $transformedItems           = $reprimandLetter instanceof LengthAwarePaginator ? $reprimandLetter->getCollection() : $reprimandLetter;
            $transformedReprimandLetter = $transformedItems->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'employee_id'   => $item->employee_id,
                    'employee_name' => $item->employee->name,
                    'letter_date'   => $item->letter_date,
                    'reason'        => $item->reason,
                    'issued_by'     => $item->issued_by,
                    'notes'         => $item->notes,
                ];
            });
            if ($reprimandLetter instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Reprimand Letter list', $reprimandLetter->setCollection($transformedReprimandLetter));
            } else {
                return ApiResponseHelper::success('Reprimand Letter list', $transformedReprimandLetter);
            }
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get reprimand letter data', $e->getMessage());
        }
    }

    public function store(ReprimandLetterStoreRequest $request)
    {
        try {
            $reprimandLetter = $this->reprimandLetterService->create($request->validated());
            return ApiResponseHelper::success('Reprimand letter data', $reprimandLetter);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get reprimand letter data', $e->getMessage());
        }
    }

    public function show($reprimandLetter)
    {
        try {
            $reprimandLetter = ReprimandLetter::find($reprimandLetter);
            if (!$reprimandLetter) {
                throw new Exception('Reprimand letter data not found');
            }
            return ApiResponseHelper::success('Reprimand letter data', $reprimandLetter);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get reprimand letter data', $e->getMessage());
        }
    }

    public function update(Request $request, ReprimandLetter $reprimandLetter)
    {
        //
    }

    public function destroy(ReprimandLetter $reprimandLetter)
    {
        //
    }
}
