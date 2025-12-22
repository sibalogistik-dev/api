<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReprimandLetterReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['nullable', 'integer'],
            'issued_by'     => ['nullable', 'integer'],
            'start_date'    => ['nullable', 'date', 'required_with:end_date'],
            'end_date'      => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
        ];
    }
}
