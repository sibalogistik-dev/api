<?php

namespace App\Services;

use App\Models\ReprimandLetter;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ReprimandLetterService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $reprimandLetter = ReprimandLetter::create($data);
            DB::commit();
            return $reprimandLetter;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save reprimand letter data: ' . $e->getMessage());
        }
    }

    public function update(ReprimandLetter $reprimandLetter, array $data)
    {
        DB::beginTransaction();
        try {
            $reprimandLetter->update($data);
            DB::commit();
            return $reprimandLetter;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update reprimand letter data: ' . $e->getMessage());
        }
    }

    public function report(array $data)
    {
        DB::beginTransaction();
        try {
            $response = ReprimandLetter::when(isset($data['start_date']) && isset($data['end_date']), function ($query) use ($data) {
                $start_date = Carbon::parse($data['start_date'])->startOfDay();
                $end_date   = Carbon::parse($data['end_date'])->endOfDay();
                $query->whereBetween('letter_date', [$start_date, $end_date]);
            })
                ->when(($employeeId = $data['employee_id'] ?? null) && $employeeId !== 'all',
                    fn($query) => $query->where('employee_id', $employeeId)
                )
                ->when(
                    ($issuerId = $data['issuer_id'] ?? null) && $issuerId !== 'all',
                    fn($query) => $query->where('issued_by', $issuerId)
                )
                ->get();
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate reprimand letter report: ' . $e->getMessage());
        }
    }

    public function document(array $data)
    {
        DB::beginTransaction();
        try {
            $response = ReprimandLetter::find($data['reprimand_letter_id']);
            if (!$response) {
                throw new Exception('Reprimand letter data not found');
            }
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate reprimand letter report: ' . $e->getMessage());
        }
    }
}
