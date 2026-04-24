<?php

namespace App\Policies;

use App\Models\InternalUser;

class InternalUserPolicy
{
    private function isAdmin(InternalUser $auth): bool
    {
        if (! $auth->relationLoaded('roles')) {
            $auth->load('roles');
        }

        return $auth->roles->pluck('name')->contains('admin');
    }

    public function viewAny(InternalUser $auth): bool
    {
        return $this->isAdmin($auth);
    }

    public function create(InternalUser $auth): bool
    {
        return $this->isAdmin($auth);
    }

    public function update(InternalUser $auth, InternalUser $target): bool
    {
        return $this->isAdmin($auth);
    }

    public function delete(InternalUser $auth, InternalUser $target): bool
    {
        return $this->isAdmin($auth);
    }
}
