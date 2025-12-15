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
            $data   = WarningLetter::when(isset($data['start_date']) && isset($data['end_date']), function ($query) use ($data) {
                $start_date = Carbon::parse($data['start_date'])->startOfDay();
                $end_date   = Carbon::parse($data['end_date'])->endOfDay();
                $query->whereBetween('letter_date', [$start_date, $end_date]);
            })
                ->when(isset($data['employee_id']), function ($query) use ($data) {
                    $query->where('employee_id', $data['employee_id']);
                })
                ->when(isset($data['issuer_id']), function ($query) use ($data) {
                    $query->where('issued_by', $data['issuer_id']);
                })
                ->get();
            DB::commit();
            return $data;
        } catch (Exception $e) {
            throw new Exception('Failed to generate warning letter report: ' . $e->getMessage());
        }
    }
}
