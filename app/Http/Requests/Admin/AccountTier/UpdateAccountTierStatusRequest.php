<?php

namespace App\Http\Requests\Admin\AccountTier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountTierStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => "Status must be 'active' or 'inactive'. A pending tier must go through the approve endpoint first.",
        ];
    }
}
