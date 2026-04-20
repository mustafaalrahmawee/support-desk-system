<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $data = $action->execute($request->email, $request->password);

        return response()->json([
            'message' => 'Login erfolgreich.',
            'data' => [
                'internal_user' => [
                    'id' => $data['internal_user']->id,
                    'first_name' => $data['internal_user']->first_name,
                    'last_name' => $data['internal_user']->last_name,
                    'email' => $data['internal_user']->email,
                    'is_active' => $data['internal_user']->is_active,
                    'roles' => $data['internal_user']->roles->pluck('name'),
                ],
                'token' => $data['token'],
            ],
        ]);
    }

    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        $action->execute($request->user());

        return response()->json(['message' => 'Logout erfolgreich.']);
    }
}
