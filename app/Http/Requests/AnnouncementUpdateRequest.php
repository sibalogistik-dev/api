<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'     => ['sometimes', 'string', 'max:255'],
            'content'   => ['sometimes', 'string'],
        ];
    }
}
