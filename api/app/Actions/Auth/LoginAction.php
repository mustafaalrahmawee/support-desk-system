<?php

namespace App\Actions\Auth;

use App\Models\InternalUser;
use App\Services\Audit\AuditService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function execute(string $email, string $password): array
    {
        $user = InternalUser::query()->where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('Ungültige Zugangsdaten.');
        }

        if (! $user->is_active) {
            throw new AuthorizationException('Ihr Benutzerkonto ist deaktiviert.');
        }

        return DB::transaction(function () use ($user) {
            $token = $user->createToken('api')->plainTextToken;

            $this->auditService->logInternalUserAction(
                user: $user,
                action: 'user_logged_in',
                auditable: $user,
                oldValues: null,
                newValues: null,
            );

            return [
                'internal_user' => $user->toProfileArray(),
                'token' => $token,
            ];
        });
    }
}
