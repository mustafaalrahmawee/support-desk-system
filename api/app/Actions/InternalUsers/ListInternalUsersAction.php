<?php

namespace App\Actions\InternalUsers;

use App\Models\InternalUser;
use Illuminate\Pagination\LengthAwarePaginator;

class ListInternalUsersAction
{
    public function execute(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 15);

        return InternalUser::with('roles')
            ->when(
                $filters['search'] ?? null,
                fn ($q, $search) => $q->where(fn ($q) => $q
                    ->where('first_name', 'ilike', "%{$search}%")
                    ->orWhere('last_name', 'ilike', "%{$search}%")
                    ->orWhere('username', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                )
            )
            ->when(
                isset($filters['is_active']),
                fn ($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN))
            )
            ->when(
                $filters['role'] ?? null,
                fn ($q, $role) => $q->whereHas('roles', fn ($q) => $q->where('name', $role))
            )
            ->paginate($perPage);
    }
}
