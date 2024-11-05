<?php

namespace App\Services\Users;

use App\Http\Requests\Users\UserStoreRequest;
use App\Models\Role;
use App\Models\UnregisteredUser;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    public function store(UserStoreRequest $request)
    {
        $request->authenticate();

        $validated = $request->validated();

        UnregisteredUser::create([
            'phone_number' => $validated['phone_number'],
            'role_id' => $validated['role'],
            'mosque_id' => $request->user()->mosque->id,
        ]);

        return response()->json([
            'message' => 'User hired successfully!',
            'phone_number' => $validated['phone_number'],
            'role' => Role::find($validated['role'])->name,
        ]);
    }

    public function destroy($id)
    {
        $userDeleted = User::find($id)?->delete();

        $unregisteredUserDeleted = UnregisteredUser::find($id)?->delete();

        if (! $userDeleted && ! $unregisteredUserDeleted) {
            return response()->json([
                'message' => 'User not found or could not be deleted',
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'User fired successfully!',
        ]);
    }
}
