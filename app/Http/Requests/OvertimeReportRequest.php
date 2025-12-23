<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date'],
            'employee_id'   => ['nullable', 'integer'],
            'approved'      => ['nullable'],
        ];
    }
}
