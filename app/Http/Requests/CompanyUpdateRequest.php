<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['nullable', 'string', 'max:255'],
            'codename'  => ['nullable', 'string', 'max:255']
        ];
    }
}
