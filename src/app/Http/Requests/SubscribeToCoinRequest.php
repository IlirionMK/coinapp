<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\NotificationFrequency;
use Illuminate\Validation\Rule;



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
            'notification_frequency' => ['nullable', Rule::in(NotificationFrequency::values())],
            'change_threshold' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
