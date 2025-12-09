<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarningLetterStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'    => ['required', 'integer', 'exists:karyawans,id'],
            'issued_by'      => ['required', 'integer', 'exists:karyawans,id'],
            'letter_date'    => ['required', 'date'],
            'reason'         => ['required', 'string'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
