<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetMaintenanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'asset_id'                  => ['sometimes', 'exists:branch_assets,id'],
            'creator_id'                => ['sometimes', 'exists:karyawans,id'],
            'maintenance_date'          => ['sometimes', 'date_format:Y-m-d'],
            'min_maintenance_cost'      => ['sometimes', 'integer', 'min:0'],
            'max_maintenance_cost'      => ['sometimes', 'integer', 'min:0'],
            'actual_maintenance_cost'   => ['sometimes', 'integer', 'min:0'],
            'description'               => ['sometimes', 'string'],
            'receipt'                   => ['sometimes', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:5120'],
            'approval_status'           => ['sometimes', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
