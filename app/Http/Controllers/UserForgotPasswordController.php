<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserForgotPasswordPhoneRequest;
use App\Http\Requests\Users\UserForgotPasswordRequest;
use App\Services\Users\UserAuthService;

class UserForgotPasswordController extends Controller
{
    private UserAuthService $userAuthService;

    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    public function phone(UserForgotPasswordPhoneRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $validated = $request->validated();

        return $this->userAuthService->phone($validated['phone_number']);
    }

    public function change(UserForgotPasswordRequest $request)
    {
        return $this->userAuthService->forgotPassword($request);
    }
}
