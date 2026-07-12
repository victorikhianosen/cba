<?php

namespace App\Services\AccountProduct;

use App\Models\AccountProduct;
use App\Services\Audit\AuditService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountProductService
{
    public function __construct(
        private AuditService $audit,
    ) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return AccountProduct::query()->with('currency')->latest()->paginate($perPage)->withQueryString();
    }

    public function find(int $id): AccountProduct
    {
        return AccountProduct::with('currency')->findOrFail($id);
    }

    public function create(array $data): AccountProduct
    {
        $actor = auth()->user();

        $data['code']       = Str::upper(trim($data['code']));
        $data['status']     = 'pending';
        $data['created_by'] = $actor?->username;

        unset($data['approved_by'], $data['approved_at']);

        $product = AccountProduct::create($data)->fresh('currency');

        $this->audit->log(
            action: 'created',
            module: 'account_products',
            auditable: $product,
            after: $product->toArray(),
            description: "Account product '{$product->name}' ({$product->code}) was created by '{$actor?->username}' and is pending approval.",
        );

        return $product;
    }

    public function update(AccountProduct $product, array $data): AccountProduct
    {
        $before = $product->toArray();
        $actor  = auth()->user();

        if (isset($data['code'])) {
            $data['code'] = Str::upper(trim($data['code']));
        }

        unset($data['status'], $data['created_by'], $data['approved_by'], $data['approved_at']);

        $product->update($data);
        $product->refresh()->load('currency');

        $this->audit->log(
            action: 'updated',
            module: 'account_products',
            auditable: $product,
            before: $before,
            after: $product->toArray(),
            description: "Account product '{$product->name}' ({$product->code}) was updated by '{$actor?->username}'.",
        );

        return $product;
    }

    public function approve(AccountProduct $product): AccountProduct
    {
        if ($product->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => ["Only pending account products can be approved. This product is currently '{$product->status}'."],
            ]);
        }

        $actor = auth()->user();

        $product->update([
            'status'      => 'active',
            'approved_by' => $actor?->username,
            'approved_at' => now(),
        ]);

        $product->refresh()->load('currency');

        $this->audit->log(
            action: 'approved',
            module: 'account_products',
            auditable: $product,
            before: ['status' => 'pending'],
            after: ['status' => $product->status, 'approved_by' => $product->approved_by, 'approved_at' => $product->approved_at],
            description: "Account product '{$product->name}' ({$product->code}) was approved by '{$actor?->username}'.",
        );

        return $product;
    }

    public function updateStatus(AccountProduct $product, string $status): AccountProduct
    {
        if ($product->status === 'pending') {
            throw ValidationException::withMessages([
                'status' => ["This account product is pending approval. Please approve it before changing its status."],
            ]);
        }

        $before = $product->status;
        $actor  = auth()->user();

        $product->update(['status' => $status]);
        $product->refresh();

        $this->audit->log(
            action: 'status_updated',
            module: 'account_products',
            auditable: $product,
            before: ['status' => $before],
            after: ['status' => $product->status],
            description: "Account product '{$product->name}' ({$product->code}) status changed from '{$before}' to '{$product->status}' by '{$actor?->username}'.",
        );

        return $product;
    }

    public function delete(AccountProduct $product): void
    {
        $name  = $product->name;
        $code  = $product->code;
        $actor = auth()->user();

        $product->delete();

        $this->audit->log(
            action: 'deleted',
            module: 'account_products',
            auditable: $product,
            before: ['name' => $name, 'code' => $code],
            description: "Account product '{$name}' ({$code}) was deleted by '{$actor?->username}'.",
        );
    }
}
