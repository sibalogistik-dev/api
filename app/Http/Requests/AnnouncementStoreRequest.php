<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'content'       => ['required', 'string'],
            'image_url'     => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'recipients'    => ['required', 'array', 'min:1'],
            'recipients.*'  => ['integer', 'exists:karyawans,id'],
        ];
    }
}
