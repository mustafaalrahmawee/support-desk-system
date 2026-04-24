<?php

namespace App\Actions\InternalUsers;

use App\Models\Actor;
use App\Models\AuditLog;
use App\Models\InternalUser;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class CreateInternalUserAction
{
    public function execute(array $data, InternalUser $createdBy): InternalUser
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $user = InternalUser::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'username'   => $data['username'],
                'email'      => $data['email'],
                'password'   => $data['password'],
                'is_active'  => true,
            ]);

            $roleIds = Role::whereIn('name', $data['roles'])->pluck('id');
            $user->roles()->sync($roleIds);

            Actor::create([
                'actor_type'         => 'internal_user',
                'internal_user_id'   => $user->id,
            ]);

            AuditLog::create([
                'internal_user_id' => $createdBy->id,
                'context_type'     => 'internal_user',
                'context_name'     => $createdBy->username,
                'action'           => 'user_created',
                'auditable_type'   => InternalUser::class,
                'auditable_id'     => $user->id,
                'old_values'       => null,
                'new_values'       => [
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'username'   => $user->username,
                    'email'      => $user->email,
                    'roles'      => $data['roles'],
                ],
            ]);

            return $user->fresh(['roles']);
        });
    }
}
