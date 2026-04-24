<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('actors')]
#[Fillable(['actor_type', 'internal_user_id', 'customer_id'])]
class Actor extends Model
{
    use SoftDeletes;

    public function internalUser(): BelongsTo
    {
        return $this->belongsTo(InternalUser::class);
    }
}
