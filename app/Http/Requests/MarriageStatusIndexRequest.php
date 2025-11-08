<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarriageStatusIndexRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string', 'max:100'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
