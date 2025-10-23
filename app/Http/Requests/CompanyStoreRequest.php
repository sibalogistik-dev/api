<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:255', 'unique:perusahaans,name'],
            'codename'  => ['required', 'string', 'max:255', 'unique:perusahaans,codename']
        ];
    }
}
