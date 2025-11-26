<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDailyReportUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'           => ['sometimes', 'integer', 'exists:karyawans,id'],
            'date'                  => ['sometimes', 'date_format:Y-m-d'],
            'job_description_id'    => ['sometimes', 'integer', 'exists:job_descriptions,id'],
            'description'           => ['sometimes', 'string'],
        ];
    }
}
