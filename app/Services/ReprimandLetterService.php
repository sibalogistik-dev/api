<?php

namespace App\Services;

use App\Models\ReprimandLetter;
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
}
