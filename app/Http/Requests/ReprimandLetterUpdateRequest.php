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
            'employee_id'    => ['sometimes', 'integer', 'exists:karyawans,id'],
            'issued_by'      => ['sometimes', 'integer', 'exists:karyawans,id'],
            'letter_date'    => ['sometimes', 'date'],
            'reason'         => ['sometimes', 'string'],
            'notes'          => ['sometimes', 'string'],
        ];
    }
}
