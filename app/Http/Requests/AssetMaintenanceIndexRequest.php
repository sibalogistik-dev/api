<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetMaintenanceIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'                 => ['nullable', 'string'],
            'paginate'          => ['nullable', 'boolean'],
            'perPage'           => ['nullable', 'integer', 'min:1'],
            'creator_id'        => ['nullable', 'string'],
            'asset_id'          => ['nullable', 'string'],
            'maintenance_date'  => ['nullable', 'date_format:Y-m-d'],
        ];
    }
}
