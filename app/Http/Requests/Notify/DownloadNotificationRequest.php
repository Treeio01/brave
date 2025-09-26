<?php

namespace App\Http\Requests\Notify;

use Illuminate\Foundation\Http\FormRequest;

class DownloadNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ua' => ['nullable', 'array'],
            'wallets' => ['nullable', 'array'],
            'tag' => ['nullable', 'string'],
            'land' => ['nullable', 'string'],
            'conferenceId' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'ua.array' => 'User agent must be an array',
            'wallets.array' => 'Wallets must be an array',
            'tag.string' => 'Tag must be a string',
            'land.string' => 'Land must be a string',
            'conferenceId.string' => 'Conference ID must be a string'
        ];
    }
}
