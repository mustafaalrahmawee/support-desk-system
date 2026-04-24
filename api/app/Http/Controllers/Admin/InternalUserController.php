<?php

namespace App\Http\Controllers\Admin;

use App\Actions\InternalUsers\CreateInternalUserAction;
use App\Actions\InternalUsers\DeactivateInternalUserAction;
use App\Actions\InternalUsers\ListInternalUsersAction;
use App\Actions\InternalUsers\UpdateInternalUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateInternalUserRequest;
use App\Http\Requests\Admin\UpdateInternalUserRequest;
use App\Models\InternalUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternalUserController extends Controller
{
    public function index(Request $request, ListInternalUsersAction $action): JsonResponse
    {
        $this->authorize('viewAny', InternalUser::class);

        $result = $action->execute($request->only(['search', 'is_active', 'role', 'page', 'per_page']));

        return response()->json([
            'data' => $result->map(fn ($user) => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'username'   => $user->username,
                'email'      => $user->email,
                'is_active'  => $user->is_active,
                'roles'      => $user->roles->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]),
            'meta' => [
                'current_page' => $result->currentPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    public function store(CreateInternalUserRequest $request, CreateInternalUserAction $action): JsonResponse
    {
        $this->authorize('create', InternalUser::class);

        $user = $action->execute($request->validated(), $request->user());

        return response()->json([
            'message' => 'Interner Benutzer erfolgreich angelegt.',
            'data'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'username'   => $user->username,
                'email'      => $user->email,
                'is_active'  => $user->is_active,
                'roles'      => $user->roles->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ], 201);
    }

    public function update(UpdateInternalUserRequest $request, InternalUser $internal_user, UpdateInternalUserAction $action): JsonResponse
    {
        $this->authorize('update', $internal_user);

        $user = $action->execute($internal_user, $request->validated(), $request->user());

        return response()->json([
            'message' => 'Interner Benutzer erfolgreich aktualisiert.',
            'data'    => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'username'   => $user->username,
                'email'      => $user->email,
                'is_active'  => $user->is_active,
                'roles'      => $user->roles->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function destroy(Request $request, string $internal_user, DeactivateInternalUserAction $action): JsonResponse
    {
        $target = InternalUser::withTrashed()->findOrFail($internal_user);

        $this->authorize('delete', $target);

        $action->execute($target, $request->user());

        return response()->json(['message' => 'Interner Benutzer erfolgreich deaktiviert.']);
    }
}
