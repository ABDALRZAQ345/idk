<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'permissions' => $user->permissions()->select('name')->pluck('name'),
        ]);
    }
}
