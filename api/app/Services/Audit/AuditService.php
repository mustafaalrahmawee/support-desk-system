<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\InternalUser;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public function logInternalUserAction(
        InternalUser $user,
        string $action,
        Model $auditable,
        ?array $oldValues,
        ?array $newValues,
    ): void {
        AuditLog::create([
            'internal_user_id' => $user->id,
            'context_type' => 'internal_user',
            'context_name' => null,
            'action' => $action,
            'auditable_type' => $auditable::class,
            'auditable_id' => $auditable->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
