<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
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
            'branch_id'     => ['sometimes', 'exists:cabangs,id'],
            'asset_type_id' => ['sometimes', 'exists:asset_types,id'],
            'is_vehicle'    => ['sometimes', 'boolean'],
            'name'          => ['sometimes', 'string', 'max:255'],
            'price'         => ['sometimes', 'numeric', 'min:0'],
            'quantity'      => ['sometimes', 'integer', 'min:1'],
            'image_path'    => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'purchase_date' => ['sometimes', 'date'],
            'description'   => ['nullable', 'string'],
        ];
    }
}
