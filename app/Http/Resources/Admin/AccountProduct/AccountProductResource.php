<?php

namespace App\Http\Resources\Admin\AccountProduct;

use App\Http\Resources\Admin\Currency\CurrencyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'currency_id'       => $this->currency_id,
            'currency'          => new CurrencyResource($this->whenLoaded('currency')),
            'name'              => $this->name,
            'code'              => $this->code,
            'description'       => $this->description,
            'interest_rate'     => $this->interest_rate,
            'interest_type'     => $this->interest_type,
            'interest_posting'  => $this->interest_posting,
            'overdraft_allowed' => $this->overdraft_allowed,
            'overdraft_limit'   => $this->overdraft_limit,
            'transfer_fee'      => $this->transfer_fee,
            'maintenance_fee'   => $this->maintenance_fee,
            'dormancy_days'     => $this->dormancy_days,
            'created_by'        => $this->created_by,
            'approved_by'       => $this->approved_by,
            'approved_at'       => $this->approved_at,
            'status'            => $this->status,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
