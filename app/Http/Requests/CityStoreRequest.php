<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'integer'],
            'province_code' => ['required', 'integer'],
            'meta'          => ['nullable', 'json']
        ];
    }
}
