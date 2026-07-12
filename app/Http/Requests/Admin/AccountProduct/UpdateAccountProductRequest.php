<?php

namespace App\Http\Requests\Admin\AccountProduct;

use App\Models\AccountProduct;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateAccountProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'currency_id' => ['sometimes', 'integer', 'exists:currencies,id'],
            'name'        => ['sometimes', 'string', 'max:255'],
            'code'        => [
                'sometimes',
                'string',
                'max:50',
                function (string $attribute, mixed $value, Closure $fail) use ($productId) {
                    $normalized = Str::upper(trim($value));

                    $exists = AccountProduct::where('code', $normalized)
                        ->where('id', '!=', $productId)
                        ->exists();

                    if ($exists) {
                        $fail("An account product with code '{$normalized}' already exists.");
                    }
                },
            ],
            'description'         => ['sometimes', 'nullable', 'string'],
            'interest_rate'       => ['sometimes', 'numeric', 'min:0'],
            'interest_type'       => ['sometimes', 'in:flat,daily,tiered'],
            'interest_posting'    => ['sometimes', 'in:daily,weekly,monthly,quarterly,annually'],
            'overdraft_allowed'   => ['sometimes', 'boolean'],
            'overdraft_limit'     => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'transfer_fee'        => ['sometimes', 'numeric', 'min:0'],
            'maintenance_fee'     => ['sometimes', 'numeric', 'min:0'],
            'dormancy_days'       => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
