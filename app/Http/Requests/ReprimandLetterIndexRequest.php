<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReprimandLetterIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id'    => ['nullable', 'integer', 'exists:karyawans,id'],
            'letter_date'    => ['nullable', 'date'],
            'issued_by'      => ['nullable', 'integer', 'exists:karyawans,id'],
            'q'             => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
