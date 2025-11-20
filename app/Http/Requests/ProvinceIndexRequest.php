<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['nullable', 'string'],
            'paginate'  => ['nullable', 'boolean'],
            'perPage'   => ['nullable', 'integer', 'min:1'],
        ];
    }
}
