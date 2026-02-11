<?php

namespace App\Services;

use App\Models\WarningLetter;
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
            $response   = WarningLetter::query()->filter($data)->get();
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
