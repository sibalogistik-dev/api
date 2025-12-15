<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarningLetterDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warning_letter_id' => ['required', 'integer']
        ];
    }
}
