<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MarriageStatusUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        $idMarriage = $this->route('marriage-status');
        return [
            'name'  =>  [
                'nullable',
                'string',
                'max:255',
                Rule::unique('marriage_statuses', 'name')->ignore($idMarriage)
            ],
        ];
    }
}
