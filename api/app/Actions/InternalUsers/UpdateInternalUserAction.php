<?php

namespace App\Actions\InternalUsers;

use App\Models\AuditLog;
use App\Models\InternalUser;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UpdateInternalUserAction
{
    public function execute(InternalUser $user, array $data, InternalUser $updatedBy): InternalUser
    {
        return DB::transaction(function () use ($user, $data, $updatedBy) {
            $fields = ['first_name', 'last_name', 'username', 'email', 'password'];
            $updateData = array_filter(
                array_intersect_key($data, array_flip($fields)),
                fn ($v) => $v !== null
            );

            // Capture old values before update; exclude password from audit (never log passwords)
            $auditableFields = array_diff(array_keys($updateData), ['password']);
            $oldValues = $user->only($auditableFields);

            if ($updateData) {
                $user->update($updateData);
            }

            if (isset($data['roles'])) {
                // Capture old roles BEFORE sync
                if (! $user->relationLoaded('roles')) {
                    $user->load('roles');
                }
                $oldValues['roles'] = $user->roles->pluck('name')->toArray();

                $roleIds = Role::whereIn('name', $data['roles'])->pluck('id');
                $user->roles()->sync($roleIds);
            }

            $newValues = array_intersect_key($updateData, array_flip($auditableFields));
            if (isset($data['roles'])) {
                $newValues['roles'] = $data['roles'];
            }

            AuditLog::create([
                'internal_user_id' => $updatedBy->id,
                'context_type'     => 'internal_user',
                'context_name'     => $updatedBy->username,
                'action'           => 'user_updated',
                'auditable_type'   => InternalUser::class,
                'auditable_id'     => $user->id,
                'old_values'       => $oldValues,
                'new_values'       => $newValues,
            ]);

            return $user->fresh(['roles']);
        });
    }
}
