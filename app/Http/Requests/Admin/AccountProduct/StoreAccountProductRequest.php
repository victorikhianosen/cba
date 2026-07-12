<?php

namespace App\Http\Requests\Admin\AccountProduct;

use App\Models\AccountProduct;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreAccountProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'name'        => ['required', 'string', 'max:255'],
            'code'        => [
                'required',
                'string',
                'max:50',
                function (string $attribute, mixed $value, Closure $fail) {
                    $normalized = Str::upper(trim($value));

                    if (AccountProduct::where('code', $normalized)->exists()) {
                        $fail("An account product with code '{$normalized}' already exists.");
                    }
                },
            ],
            'description'        => ['nullable', 'string'],
            'interest_rate'       => ['sometimes', 'numeric', 'min:0'],
            'interest_type'       => ['sometimes', 'in:flat,daily,tiered'],
            'interest_posting'    => ['sometimes', 'in:daily,weekly,monthly,quarterly,annually'],
            'overdraft_allowed'   => ['sometimes', 'boolean'],
            'overdraft_limit'     => ['nullable', 'numeric', 'min:0'],
            'transfer_fee'        => ['sometimes', 'numeric', 'min:0'],
            'maintenance_fee'     => ['sometimes', 'numeric', 'min:0'],
            'dormancy_days'       => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
