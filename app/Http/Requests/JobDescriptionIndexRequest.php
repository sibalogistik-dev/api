<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobDescriptionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q'                 => ['nullable', 'string', 'max:100'],
            'job_title_id'      => ['nullable', 'integer'],
            'priority_level'    => ['nullable', 'string'],
            'paginate'          => ['nullable', 'boolean'],
            'perPage'           => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
