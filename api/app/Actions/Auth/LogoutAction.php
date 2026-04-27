<?php

namespace App\Actions\Auth;

use App\Models\InternalUser;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\DB;

class LogoutAction
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    public function execute(InternalUser $user): void
    {
        DB::transaction(function () use ($user) {
            $user->currentAccessToken()->delete();

            $this->auditService->logInternalUserAction(
                user: $user,
                action: 'user_logged_out',
                auditable: $user,
                oldValues: null,
                newValues: null,
            );
        });
    }
}
