<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(
        LoginRequest $request,
        LoginAction $action
    ): JsonResponse {
        $result = $action->execute(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );

        return response()->json([
            'message' => 'Vorgang erfolgreich.',
            'data' => $result,
        ], 200);
    }
}
