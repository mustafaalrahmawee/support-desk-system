<?php

namespace App\Actions\Auth;

use App\Models\AuditLog;
use App\Models\InternalUser;
use Illuminate\Support\Facades\DB;

class UpdateOwnProfileAction
{
    public function execute(InternalUser $user, array $data): InternalUser
    {
        return DB::transaction(function () use ($user, $data) {
            $oldValues = $user->only(array_keys($data));

            $user->update($data);

            AuditLog::create([
                'internal_user_id' => $user->id,
                'context_type' => 'internal_user',
                'context_name' => $user->username,
                'action' => 'profile_update',
                'auditable_type' => InternalUser::class,
                'auditable_id' => $user->id,
                'old_values' => $oldValues,
                'new_values' => $data,
            ]);

            return $user->fresh(['roles']);
        });
    }
}
