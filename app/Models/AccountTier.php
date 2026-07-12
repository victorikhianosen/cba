<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTier extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'level'                          => 'integer',
            'minimum_balance'                => 'decimal:4',
            'maximum_balance'                => 'decimal:4',
            'maximum_single_transaction'     => 'decimal:4',
            'daily_transaction_limit'        => 'decimal:4',
            'daily_withdrawal_limit'         => 'decimal:4',
            'maximum_transactions_per_day'   => 'integer',
            'bvn_required'                   => 'boolean',
            'nin_required'                   => 'boolean',
            'valid_id_required'              => 'boolean',
            'address_verification_required'  => 'boolean',
            'approved_at'                    => 'datetime',
        ];
    }
}
