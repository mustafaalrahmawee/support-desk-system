<?php

namespace App\Actions\Auth;

use App\Models\InternalUser;

class ShowOwnProfileAction
{
    public function execute(InternalUser $user): InternalUser
    {
        $user->load('roles');

        return $user;
    }
}
