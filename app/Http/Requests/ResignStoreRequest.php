<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResignStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['required', 'integer', 'exists:karyawans,id'],
            'date'          => ['required', 'date', 'date_format:Y-m-d'],
            'status'        => ['required', 'string', 'in:waiting,rejected,accepted'],
            'description'   => ['required', 'string']
        ];
    }
}
