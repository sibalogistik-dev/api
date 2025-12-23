<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchAssetIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer'],
            'branch_id'     => ['nullable', 'integer', 'exists:cabangs,id'],
            'asset_type_id' => ['nullable', 'integer', 'exists:asset_types,id'],
            'is_vehicle'            => [
                'nullable',
                Rule::when(
                    $this->input('is_vehicle') !== 'all',
                    ['boolean']
                ),
            ],
            'start_date'    => ['nullable', 'date', 'required_with:end_date'],
            'end_date'      => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
        ];
    }
}
