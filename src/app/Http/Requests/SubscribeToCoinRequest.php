<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeToCoinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coin_id' => ['required', 'exists:coins,id'],
            'notification_frequency' => ['nullable', 'in:instant,daily,none'],
            'change_threshold' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
