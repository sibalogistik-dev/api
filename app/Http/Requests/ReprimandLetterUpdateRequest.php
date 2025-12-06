<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReprimandLetterUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'    => ['required', 'integer', 'exists:employees,id'],
            'letter_date'    => ['required', 'date'],
            'reason'         => ['required', 'string'],
            'issued_by'      => ['required', 'string'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
