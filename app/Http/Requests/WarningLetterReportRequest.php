<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarningLetterReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'   => ['nullable', 'integer'],
            'from_date'     => ['sometimes', 'date'],
            'to_date'       => ['nullable', 'date', 'after_or_equal:from_date'],
        ];
    }
}
