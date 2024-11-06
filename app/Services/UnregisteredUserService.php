<?php

namespace App\Services;

use App\Http\Requests\UnregisteredUsers\UnregisteredUserStoreRequest;
use App\Models\Role;
use App\Models\UnregisteredUser;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UnregisteredUserService
{
    private VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    public function phone(string $phoneNumber)
    {
        $this->verificationCodeService->send($phoneNumber);

        return response()->json([
            'message' => 'Verification code send successfully',
        ]);
    }

    public function store(UnregisteredUserStoreRequest $request)
    {
        $validated = $request->validated();

        $this->verificationCodeService->verify($validated['phone_number'], $validated['code']);

        UnregisteredUser::create([
            'phone_number' => $validated['phone_number'],
            'role_id' => $validated['role'],
            'mosque_id' => $request->user()->mosque->id,
        ]);

        $this->verificationCodeService->delete($validated['phone_number']);

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

        if (!$userDeleted && !$unregisteredUserDeleted) {
            return response()->json([
                'message' => 'User not found or could not be deleted',
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'User fired successfully!',
        ]);
    }
}