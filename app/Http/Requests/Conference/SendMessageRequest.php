<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sender' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
            'time' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'sender.required' => 'Sender is required',
            'sender.string' => 'Sender must be a string',
            'sender.max' => 'Sender must not exceed 255 characters',
            'text.required' => 'Text is required',
            'text.string' => 'Text must be a string',
            'time.string' => 'Time must be a string'
        ];
    }
}
