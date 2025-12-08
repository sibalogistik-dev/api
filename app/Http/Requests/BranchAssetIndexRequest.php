<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchAssetIndexRequest extends FormRequest
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
            'q'             => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer'],
        ];
    }
}
