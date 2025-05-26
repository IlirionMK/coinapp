<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoinSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notification_frequency' => ['required', Rule::in(['instant', 'daily', 'none'])],
            'change_threshold' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
