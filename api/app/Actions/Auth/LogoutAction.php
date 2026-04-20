<?php

namespace App\Actions\Auth;

use App\Models\AuditLog;
use App\Models\InternalUser;

class LogoutAction
{
    public function execute(InternalUser $user): void
    {
        AuditLog::create([
            'internal_user_id' => $user->id,
            'context_type' => 'internal_user',
            'context_name' => $user->username,
            'action' => 'logout',
            'auditable_type' => InternalUser::class,
            'auditable_id' => $user->id,
            'old_values' => null,
            'new_values' => null,
        ]);

        $user->currentAccessToken()->delete();
    }
}
