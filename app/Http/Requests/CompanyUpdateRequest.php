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
            'name'          => ['sometimes', 'string', 'max:255'],
            'codename'      => ['sometimes', 'string', 'max:255'],
            'email'         => ['sometimes', 'string', 'email', 'max:255'],
            'website'       => ['sometimes', 'string', 'max:255'],
            'company_brand' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
