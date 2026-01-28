<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'                 => ['nullable', 'string'],
            'paginate'          => ['nullable', 'boolean'],
            'perPage'           => ['nullable', 'integer', 'min:1'],
            'employee_id'       => ['nullable', 'integer']
        ];
    }
}
