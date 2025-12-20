<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VillageUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => ['sometimes', 'string', 'max:255'],
            'code'          => ['sometimes', 'integer'],
            'district_code' => ['sometimes', 'integer'],
            'meta'          => ['nullable', 'json']
        ];
    }
}
