<?php

namespace App\Actions\Auth;

use App\Models\InternalUser;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\DB;

class UpdateOwnProfileAction
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    public function execute(InternalUser $user, array $data): InternalUser
    {
        return DB::transaction(function () use ($user, $data) {
            $oldValues = $user->only(array_keys($data));

            $user->update($data);
            $user->refresh();

            $newValues = $user->only(array_keys($data));

            $this->auditService->logInternalUserAction(
                user: $user,
                action: 'profile_updated',
                auditable: $user,
                oldValues: $oldValues,
                newValues: $newValues,
            );

            return $user;
        });
    }
}
