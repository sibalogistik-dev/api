<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResignIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'         => ['nullable', 'string'],
            'paginate'  => ['nullable', 'boolean'],
            'perPage'   => ['nullable', 'integer', 'min:1'],
        ];
    }
}
