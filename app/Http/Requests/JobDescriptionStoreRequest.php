<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobDescriptionStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'job_title_id'      => ['required', 'integer'],
            'task_name'         => ['required', 'string', 'max:255'],
            'task_detail'       => ['required', 'string', 'max:255'],
            'priority_level'    => ['required', 'string'],
        ];
    }
}
