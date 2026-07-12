<?php

namespace App\Services\AccountTier;

use App\Models\AccountTier;
use App\Services\Audit\AuditService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountTierService
{
    public function __construct(
        private AuditService $audit,
    ) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return AccountTier::query()->latest()->paginate($perPage)->withQueryString();
    }

    public function find(int $id): AccountTier
    {
        return AccountTier::findOrFail($id);
    }

    /**
     * Only `name` and `level` are accepted from the client — everything
     * else (code, status, created_by, ...) is controlled server-side.
     */
    public function create(array $data): AccountTier
    {
        $actor = auth()->user();

        $tier = AccountTier::create([
            'name'       => trim(preg_replace('/\s+/', ' ', $data['name'])),
            'level'      => $data['level'],
            'code'       => $this->generateUniqueCode($data['name'], $data['level']),
            'status'     => 'pending',
            'created_by' => $actor?->username,
        ])->fresh();

        $this->audit->log(
            action: 'created',
            module: 'account_tiers',
            auditable: $tier,
            after: $tier->toArray(),
            description: "Account tier '{$tier->name}' ({$tier->code}) was created by '{$actor?->username}' and is pending approval.",
        );

        return $tier;
    }

    public function update(AccountTier $tier, array $data): AccountTier
    {
        $before = $tier->toArray();
        $actor  = auth()->user();

        if (isset($data['name'])) {
            $data['name'] = trim(preg_replace('/\s+/', ' ', $data['name']));
        }

        unset($data['code'], $data['status'], $data['created_by'], $data['approved_by'], $data['approved_at']);

        $tier->update($data);
        $tier->refresh();

        $this->audit->log(
            action: 'updated',
            module: 'account_tiers',
            auditable: $tier,
            before: $before,
            after: $tier->toArray(),
            description: "Account tier '{$tier->name}' ({$tier->code}) was updated by '{$actor?->username}'.",
        );

        return $tier;
    }

    public function approve(AccountTier $tier): AccountTier
    {
        if ($tier->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => ["Only pending account tiers can be approved. This tier is currently '{$tier->status}'."],
            ]);
        }

        $actor = auth()->user();

        $tier->update([
            'status'      => 'active',
            'approved_by' => $actor?->username,
            'approved_at' => now(),
        ]);

        $tier->refresh();

        $this->audit->log(
            action: 'approved',
            module: 'account_tiers',
            auditable: $tier,
            before: ['status' => 'pending'],
            after: ['status' => $tier->status, 'approved_by' => $tier->approved_by, 'approved_at' => $tier->approved_at],
            description: "Account tier '{$tier->name}' ({$tier->code}) was approved by '{$actor?->username}'.",
        );

        return $tier;
    }

    public function updateStatus(AccountTier $tier, string $status): AccountTier
    {
        if ($tier->status === 'pending') {
            throw ValidationException::withMessages([
                'status' => ['This account tier is pending approval. Please approve it before changing its status.'],
            ]);
        }

        $before = $tier->status;
        $actor  = auth()->user();

        $tier->update(['status' => $status]);
        $tier->refresh();

        $this->audit->log(
            action: 'status_updated',
            module: 'account_tiers',
            auditable: $tier,
            before: ['status' => $before],
            after: ['status' => $tier->status],
            description: "Account tier '{$tier->name}' ({$tier->code}) status changed from '{$before}' to '{$tier->status}' by '{$actor?->username}'.",
        );

        return $tier;
    }

    public function delete(AccountTier $tier): void
    {
        $name  = $tier->name;
        $code  = $tier->code;
        $actor = auth()->user();

        $tier->delete();

        $this->audit->log(
            action: 'deleted',
            module: 'account_tiers',
            auditable: $tier,
            before: ['name' => $name, 'code' => $code],
            description: "Account tier '{$name}' ({$code}) was deleted by '{$actor?->username}'.",
        );
    }

    private function generateUniqueCode(string $name, int $level): string
    {
        $base = Str::upper(Str::slug($name, '_')) ?: 'TIER';
        $code = "{$base}_{$level}";

        $suffix = 1;

        while (AccountTier::where('code', $code)->exists()) {
            $code = "{$base}_{$level}_{$suffix}";
            $suffix++;
        }

        return $code;
    }
}
