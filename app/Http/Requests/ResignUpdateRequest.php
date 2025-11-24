<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResignUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['sometimes', 'integer', 'exists:karyawans,id'],
            'date'          => ['sometimes', 'date', 'date_format:Y-m-d'],
            'status'        => ['sometimes', 'string', 'in:waiting,rejected,accepted'],
            'description'   => ['sometimes', 'string']
        ];
    }
}
