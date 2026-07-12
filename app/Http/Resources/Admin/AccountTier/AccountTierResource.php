<?php

namespace App\Http\Resources\Admin\AccountTier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountTierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                              => $this->id,
            'name'                            => $this->name,
            'code'                            => $this->code,
            'level'                           => $this->level,
            'description'                     => $this->description,
            'minimum_balance'                 => $this->minimum_balance,
            'maximum_balance'                 => $this->maximum_balance,
            'maximum_single_transaction'      => $this->maximum_single_transaction,
            'daily_transaction_limit'         => $this->daily_transaction_limit,
            'daily_withdrawal_limit'          => $this->daily_withdrawal_limit,
            'maximum_transactions_per_day'    => $this->maximum_transactions_per_day,
            'bvn_required'                    => $this->bvn_required,
            'nin_required'                    => $this->nin_required,
            'valid_id_required'               => $this->valid_id_required,
            'address_verification_required'   => $this->address_verification_required,
            'created_by'                      => $this->created_by,
            'approved_by'                     => $this->approved_by,
            'approved_at'                     => $this->approved_at,
            'status'                          => $this->status,
            'created_at'                      => $this->created_at,
            'updated_at'                      => $this->updated_at,
        ];
    }
}
