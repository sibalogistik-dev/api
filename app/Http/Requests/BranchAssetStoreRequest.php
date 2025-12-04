<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchAssetStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branch_id'     => ['required', 'exists:cabangs,id'],
            'asset_type_id' => ['required', 'exists:asset_types,id'],
            'is_vehicle'    => ['required', 'boolean'],
            'name'          => ['required', 'string', 'max:255'],
            'price'         => ['required', 'numeric', 'min:0'],
            'purchase_date' => ['required', 'date'],
            'description'   => ['nullable', 'string'],
        ];
    }
}
