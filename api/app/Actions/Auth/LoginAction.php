<?php

namespace App\Actions\Auth;

use App\Models\AuditLog;
use App\Models\InternalUser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function execute(string $email, string $password): array
    {
        $user = InternalUser::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException();
        }

        if (! $user->is_active) {
            throw new HttpResponseException(
                response()->json(['message' => 'Konto deaktiviert.'], 403)
            );
        }

        $token = $user->createToken('api')->plainTextToken;

        $user->load('roles');

        AuditLog::create([
            'internal_user_id' => $user->id,
            'context_type' => 'internal_user',
            'context_name' => $user->username,
            'action' => 'login',
            'auditable_type' => InternalUser::class,
            'auditable_id' => $user->id,
            'old_values' => null,
            'new_values' => null,
        ]);

        return [
            'internal_user' => $user,
            'token' => $token,
        ];
    }
}
