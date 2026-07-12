<?php

namespace App\Http\Requests\Admin\AccountProduct;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountProductStatusRequest extends FormRequest
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
            'status.in' => "Status must be 'active' or 'inactive'. A pending product must go through the approve endpoint first.",
        ];
    }
}
