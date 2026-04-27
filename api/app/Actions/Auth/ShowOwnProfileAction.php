<?php

namespace App\Actions\Auth;

use App\Models\InternalUser;

class ShowOwnProfileAction
{
    public function execute(InternalUser $user): array
    {
        return $user->toProfileArray();
    }
}
