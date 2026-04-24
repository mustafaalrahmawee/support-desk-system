<?php

namespace App\Actions\InternalUsers;

use App\Models\AuditLog;
use App\Models\InternalUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeactivateInternalUserAction
{
    public function execute(InternalUser $user, InternalUser $deactivatedBy): void
    {
        if ($user->id === $deactivatedBy->id) {
            throw new HttpResponseException(
                response()->json(['message' => 'Selbst-Deaktivierung ist nicht erlaubt.'], 409)
            );
        }

        if (! $user->is_active) {
            throw new HttpResponseException(
                response()->json(['message' => 'Der Benutzer ist bereits deaktiviert.'], 409)
            );
        }

        DB::transaction(function () use ($user, $deactivatedBy) {
            $user->update(['is_active' => false]);
            $user->delete();

            $user->actor?->delete();

            if (Schema::hasTable('tickets')) {
                DB::table('tickets')
                    ->where('assigned_internal_user_id', $user->id)
                    ->update(['assigned_internal_user_id' => null]);
            }

            AuditLog::create([
                'internal_user_id' => $deactivatedBy->id,
                'context_type'     => 'internal_user',
                'context_name'     => $deactivatedBy->username,
                'action'           => 'user_deactivated',
                'auditable_type'   => InternalUser::class,
                'auditable_id'     => $user->id,
                'old_values'       => ['is_active' => true],
                'new_values'       => ['is_active' => false],
            ]);
        });
    }
}
