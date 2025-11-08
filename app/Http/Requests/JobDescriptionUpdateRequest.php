<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobDescriptionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'job_title_id'      => ['nullable', 'integer'],
            'task_name'         => ['nullable', 'string', 'max:255'],
            'task_detail'       => ['nullable', 'string', 'max:255'],
            'priority_level'    => ['nullable', 'string'],
        ];
    }
}
