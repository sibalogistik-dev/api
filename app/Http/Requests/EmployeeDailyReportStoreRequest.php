<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDailyReportStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'           => ['required', 'integer', 'exists:karyawans,id'],
            'date'                  => ['required', 'date_format:Y-m-d'],
            'job_description_id'    => ['required', 'integer', 'exists:job_descriptions,id'],
            'description'           => ['required', 'string'],
        ];
    }
}
