<?php

namespace App\Services\Users;

use App\Exceptions\BadCredentialsException;
use App\Http\Requests\Users\UserForgotPasswordRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use App\Models\UnregisteredUser;
use App\Models\User;
use App\Services\VerificationCodeService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class UserAuthService
{
    private VerificationCodeService $verificationCodeService;

    /**
     * Inject Dependencies
     * @param \App\Services\VerificationCodeService $verificationCodeService
     */
    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * Send a verification code
     * @param string $phoneNumber
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function phone(string $phoneNumber)
    {
        $this->verificationCodeService->send($phoneNumber);

        return response()->json([
            'message' => 'Verification code send successfully',
        ]);
    }

    /**
     * Verify phone number and code then
     * register user with same id in unregistered users table then
     * delete unregistered user and verification code
     * @param \App\Http\Requests\Users\UserRegisterRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();

        $this->verificationCodeService->verify($validated['phone_number'], $validated['code']);

        $unregisteredUser = UnregisteredUser::where(
            'phone_number',
            $validated['phone_number']
        )->firstOrFail();

        $user = User::create([
            'id' => $unregisteredUser->id,
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role_id' => $unregisteredUser->role->id,
            'mosque_id' => $unregisteredUser->mosque->id,
        ]);

        $unregisteredUser->delete();

        $this->verificationCodeService->delete($validated['phone_number']);

        $token = $user->createToken('api_token')->plainTextToken;

        RateLimiter::clear($request->ip());

        return response()->json([
            'message' => 'User registered successfully',
            'user_id' => $user->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Check password match then authenticate user
     * @param \App\Http\Requests\Users\UserLoginRequest $request
     * @throws \App\Exceptions\BadCredentialsException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where(
            'phone_number',
            $validated['phone_number']
        )->firstOrFail();

        if (!Hash::check($validated['password'], $user->password)) {
            throw new BadCredentialsException;
        }

        $token = $user->createToken('api_token')->plainTextToken;

        RateLimiter::clear($request->ip());

        return response()->json([
            'message' => 'User logged in successfully',
            'user_id' => $user->id,
            'access_token' => $token,
            'token_type' => "Bearer",
        ]);
    }

    public function forgotPassword(UserForgotPasswordRequest $request)
    {
        $validated = $request->validated();

        $this->verificationCodeService->verify($validated['phone_number'], $validated['code']);

        $user = User::where(
            'phone_number',
            $validated['phone_number']
        )->firstOrFail();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->verificationCodeService->delete($validated['phone_number']);

        RateLimiter::clear($request->ip());

        return response()->json([
            'message' => 'User password changed successfully',
        ]);
    }

    /**
     * Delete current access token
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(User $user)
    {
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }

}