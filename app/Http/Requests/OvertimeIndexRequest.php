<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string'],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date'],
            'employee_id'   => ['nullable', 'integer'],
            'approved'      => ['nullable'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer'],
        ];
    }
}
