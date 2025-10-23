<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'        => ['nullable', 'string', 'max:100'],
            'branch'   => ['nullable', 'string'],
            'paginate' => ['nullable', 'boolean'],
            'perPage'  => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
