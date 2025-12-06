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
}
