<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ShowOwnProfileAction;
use App\Actions\Auth\UpdateOwnProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateOwnProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, ShowOwnProfileAction $action): JsonResponse
    {
        $user = $action->execute($request->user());

        return response()->json([
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'roles' => $user->roles->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function update(UpdateOwnProfileRequest $request, UpdateOwnProfileAction $action): JsonResponse
    {
        $user = $action->execute($request->user(), $request->validated());

        return response()->json([
            'message' => 'Profil erfolgreich aktualisiert.',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'roles' => $user->roles->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }
}
