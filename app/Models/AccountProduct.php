<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountProduct extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'interest_rate'      => 'decimal:4',
            'overdraft_allowed'  => 'boolean',
            'overdraft_limit'    => 'decimal:2',
            'transfer_fee'       => 'decimal:2',
            'maintenance_fee'    => 'decimal:2',
            'dormancy_days'      => 'integer',
            'approved_at'        => 'datetime',
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
