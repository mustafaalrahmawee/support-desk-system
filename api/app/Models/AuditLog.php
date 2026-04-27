<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('audit_logs')]
#[Fillable([
    'internal_user_id',
    'context_type',
    'context_name',
    'action',
    'auditable_type',
    'auditable_id',
    'old_values',
    'new_values',
])]
class AuditLog extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function internalUser(): BelongsTo
    {
        return $this->belongsTo(InternalUser::class);
    }
}
