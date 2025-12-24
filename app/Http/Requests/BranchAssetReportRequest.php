<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchAssetReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branch_id'     => ['nullable', 'integer'],
            'asset_type_id' => ['nullable', 'integer'],
            'is_vehicle'    => ['nullable', 'string'],
            'start_date'    => ['nullable', 'date', 'required_with:end_date'],
            'end_date'      => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
        ];
    }
}
