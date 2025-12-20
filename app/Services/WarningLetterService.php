<?php

namespace App\Services;

use App\Models\WarningLetter;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class WarningLetterService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $warningLetter = WarningLetter::create($data);
            DB::commit();
            return $warningLetter;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save warning letter data: ' . $e->getMessage());
        }
    }

    public function update(WarningLetter $warningLetter, array $data)
    {
        DB::beginTransaction();
        try {
            $warningLetter->update($data);
            DB::commit();
            return $warningLetter;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update warning letter data: ' . $e->getMessage());
        }
    }

    public function report(array $data)
    {
        DB::beginTransaction();
        try {
            $response   = WarningLetter::when(isset($data['start_date']) && isset($data['end_date']), function ($query) use ($data) {
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
                ->when(
                    ($letterNumber = $data['letter_number'] ?? null) && $letterNumber !== 'all',
                    fn($query) => $query->where('letter_number', $letterNumber)
                )
                ->get();
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate warning letter report: ' . $e->getMessage());
        }
    }

    public function document(array $data)
    {
        DB::beginTransaction();
        try {
            $response = WarningLetter::find($data['warning_letter_id']);
            if (!$response) {
                throw new Exception('Warning letter data not found');
            }
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate warning letter report: ' . $e->getMessage());
        }
    }
}
