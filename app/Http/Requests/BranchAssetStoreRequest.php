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
            'branch_id'     => ['required', 'exists:cabangs,id'],
            'asset_type_id' => ['required', 'exists:asset_types,id'],
            'is_vehicle'    => ['sometimes', 'boolean'],
            'name'          => ['required', 'string', 'max:255'],
            'price'         => ['required', 'numeric', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:1'],
            'image_path'    => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'purchase_date' => ['sometimes', 'date'],
            'description'   => ['nullable', 'string'],
        ];
    }
}
