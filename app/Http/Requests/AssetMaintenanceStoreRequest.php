<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetMaintenanceStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'asset_id'                  => ['required', 'exists:branch_assets,id'],
            'creator_id'                => ['required', 'exists:karyawans,id'],
            'maintenance_date'          => ['required', 'date_format:Y-m-d'],
            'min_maintenance_cost'      => ['required', 'integer', 'min:0'],
            'max_maintenance_cost'      => ['nullable', 'integer', 'min:0'],
            'actual_maintenance_cost'   => ['nullable', 'integer', 'min:0'],
            'description'               => ['required', 'string'],
            'receipt'                   => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:5120'],
            'approval_status'           => ['nullable', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
