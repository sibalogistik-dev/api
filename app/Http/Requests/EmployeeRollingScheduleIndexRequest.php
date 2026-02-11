<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRollingScheduleIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string'],
            'employee_id'   => ['nullable', 'string'],
            'start_date'    => ['nullable', 'date', 'required_with:end_date'],
            'end_date'      => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1']
        ];
    }
}
