<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class CreateConferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
            'ref' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.string' => 'Title must be a string',
            'title.max' => 'Title must not exceed 255 characters',
            'domain.string' => 'Domain must be a string',
            'domain.max' => 'Domain must not exceed 255 characters',
            'ref.string' => 'Reference must be a string',
            'ref.max' => 'Reference must not exceed 255 characters'
        ];
    }
}
