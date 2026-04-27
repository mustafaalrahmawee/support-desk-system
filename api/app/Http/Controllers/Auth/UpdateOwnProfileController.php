<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\UpdateOwnProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateOwnProfileRequest;
use Illuminate\Http\JsonResponse;

class UpdateOwnProfileController extends Controller
{
    public function __invoke(
        UpdateOwnProfileRequest $request,
        UpdateOwnProfileAction $action
    ): JsonResponse {
        $user = $action->execute($request->user(), $request->validated());

        return response()->json([
            'message' => 'Vorgang erfolgreich.',
            'data' => $user->toProfileArray(),
        ]);
    }
}
