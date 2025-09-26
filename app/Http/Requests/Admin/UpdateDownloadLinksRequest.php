<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDownloadLinksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'windows' => ['nullable', 'string'],
            'mac' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'windows.string' => 'Windows link must be a string',
            'mac.string' => 'Mac link must be a string'
        ];
    }
}
