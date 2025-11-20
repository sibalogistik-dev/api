<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'code'      => ['required', 'integer'],
            'city_code' => ['required', 'integer'],
            'meta'      => ['nullable', 'json']
        ];
    }
}
