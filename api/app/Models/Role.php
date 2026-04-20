<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Table('roles')]
#[Fillable(['name', 'display_name'])]
class Role extends Model
{
    public function internalUsers(): BelongsToMany
    {
        return $this->belongsToMany(InternalUser::class, 'internal_user_roles');
    }
}
