<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'max:255', Rule::unique('perusahaans', 'name')->whereNull('deleted_at'),],
            'codename'      => ['required', 'string', 'max:255', Rule::unique('perusahaans', 'codename')->whereNull('deleted_at'),],
            'email'         => ['nullable', 'string', 'email', 'max:255'],
            'website'       => ['nullable', 'string', 'max:255'],
            'company_brand' => ['nullable', 'string', 'max:255'],
        ];
    }
}
