<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'text' => 'required|string|max:500',
            'channel' => 'required|string|in:email,telegram',
        ];
    }
}
