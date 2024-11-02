<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserRegisterPhoneRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use App\Services\Users\UserAuthService;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{

    private UserAuthService $userAuthService;

    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    public function phone(UserRegisterPhoneRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $validated = $request->validated();

        return $this->userAuthService->phone($validated['phone_number']);
    }

    public function register(UserRegisterRequest $request)
    {
        return $this->userAuthService->register($request);
    }

    public function login(UserLoginRequest $request)
    {
        return $this->userAuthService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->userAuthService->logout($request->user());
    }
}
