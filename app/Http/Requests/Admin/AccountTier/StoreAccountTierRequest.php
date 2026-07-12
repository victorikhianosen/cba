<?php

namespace App\Http\Requests\Admin\AccountTier;

use App\Models\AccountTier;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreAccountTierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) {
                    $normalized = trim(preg_replace('/\s+/', ' ', $value));

                    if (AccountTier::whereRaw('LOWER(name) = ?', [Str::lower($normalized)])->exists()) {
                        $fail("An account tier named '{$normalized}' already exists.");
                    }
                },
            ],
            'level' => [
                'required',
                'integer',
                'min:0',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (AccountTier::where('level', $value)->exists()) {
                        $fail("An account tier with level '{$value}' already exists.");
                    }
                },
            ],
        ];
    }
}
