<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReprimandLetterDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'reprimand_letter_id' => ['required', 'integer']
        ];
    }
}
