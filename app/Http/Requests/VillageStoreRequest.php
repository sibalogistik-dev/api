<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VillageStoreRequest extends FormRequest
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
            'district_code' => ['required', 'integer'],
            'meta'          => ['nullable', '']
        ];
    }
}
