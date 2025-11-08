<?php

namespace App\Services;

use App\Models\Perusahaan;
use Exception;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $company = Perusahaan::create($data);
            DB::commit();
            return $company;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save company data: ' . $e->getMessage());
        }
    }

    public function update(Perusahaan $perusahaan, array $data)
    {
        DB::beginTransaction();
        try {
            $perusahaan->update($data);
            DB::commit();
            return $perusahaan;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update company data: ' . $e->getMessage());
        }
    }
}
