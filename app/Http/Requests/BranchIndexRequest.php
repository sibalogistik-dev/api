<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchIndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'q'             => ['nullable', 'string'],
            'company_id'    => ['nullable', 'string'],
            'paginate'      => ['nullable', 'boolean'],
            'perPage'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
