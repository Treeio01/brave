<?php

namespace App\Http\Requests\Bot;

use Illuminate\Foundation\Http\FormRequest;

class SendBotMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Text is required',
            'text.string' => 'Text must be a string'
        ];
    }
}
