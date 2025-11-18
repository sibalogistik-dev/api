<?php

namespace App\Services;

use App\Models\Agama;
use Exception;
use Illuminate\Support\Facades\DB;

class ReligionService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                return Agama::create($data);
            } catch (Exception $e) {
                throw new Exception('Failed to save religion data: ' . $e->getMessage(), 500);
            }
        });
    }

    public function update(Agama $religion, array $data)
    {
        return DB::transaction(function () use ($religion, $data) {
            try {
                $religion->update($data);
                return $religion;
            } catch (Exception $e) {
                throw new Exception('Failed to update religion data: ' . $e->getMessage(), 500);
            }
        });
    }
}
