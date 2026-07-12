<?php

namespace App\Http\Requests\Admin\AccountTier;

use App\Models\AccountTier;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateAccountTierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tierId = $this->route('id');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($tierId) {
                    $normalized = trim(preg_replace('/\s+/', ' ', $value));

                    $exists = AccountTier::whereRaw('LOWER(name) = ?', [Str::lower($normalized)])
                        ->where('id', '!=', $tierId)
                        ->exists();

                    if ($exists) {
                        $fail("An account tier named '{$normalized}' already exists.");
                    }
                },
            ],
            'level' => [
                'sometimes',
                'integer',
                'min:0',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($tierId) {
                    $exists = AccountTier::where('level', $value)
                        ->where('id', '!=', $tierId)
                        ->exists();

                    if ($exists) {
                        $fail("An account tier with level '{$value}' already exists.");
                    }
                },
            ],
            'description'                     => ['sometimes', 'nullable', 'string'],
            'minimum_balance'                  => ['sometimes', 'numeric', 'min:0'],
            'maximum_balance'                  => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'maximum_single_transaction'       => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'daily_transaction_limit'          => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'daily_withdrawal_limit'           => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'maximum_transactions_per_day'     => ['sometimes', 'nullable', 'integer', 'min:0'],
            'bvn_required'                     => ['sometimes', 'boolean'],
            'nin_required'                     => ['sometimes', 'boolean'],
            'valid_id_required'                => ['sometimes', 'boolean'],
            'address_verification_required'    => ['sometimes', 'boolean'],
        ];
    }
}
