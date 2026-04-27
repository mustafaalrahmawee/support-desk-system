<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ShowOwnProfileAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowOwnProfileController extends Controller
{
    public function __invoke(Request $request, ShowOwnProfileAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Vorgang erfolgreich.',
            'data' => $action->execute($request->user()),
        ]);
    }
}
