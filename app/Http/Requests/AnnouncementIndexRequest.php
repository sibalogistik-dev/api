<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'                 => ['nullable', 'string'],
            'paginate'          => ['nullable', 'boolean'],
            'perPage'           => ['nullable', 'integer', 'min:1'],
            'start_date'        => ['nullable', 'date'],
            'end_date'          => ['nullable', 'date'],
            'employee_id'       => ['nullable', 'integer']
        ];
    }
}
