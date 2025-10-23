<?php

namespace App\Services;

use App\Models\Cabang;
use Exception;
use Illuminate\Support\Facades\DB;

class BranchService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $cabang = Cabang::create($data);
            DB::commit();
            return $cabang;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save branch data: ' . $e->getMessage());
        }
    }

    public function update(Cabang $cabang, array $data)
    {
        DB::beginTransaction();
        try {
            $cabangData = [
                'name'          => $data['name'],
                'address'       => $data['address'],
                'telephone'     => $data['telephone'],
                'village_id'    => $data['village_id'],
                'company_id'    => $data['company_id'],
                'start_time'    => $data['start_time'],
                'end_time'      => $data['end_time'],
                'latitude'      => $data['latitude'],
                'longitude'     => $data['longitude'],
            ];
            $cabang->update($cabangData);
            DB::commit();
            return $cabang;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update branch data: ' . $e->getMessage());
        }
    }
}
