<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Students\StudentLoginPhoneRequest;
use App\Http\Requests\Students\StudentLoginRequest;
use App\Services\Students\StudentAuthService;
use Illuminate\Http\Request;

class StudentLoginController extends Controller
{
    private StudentAuthService $studentAuthService;

    public function __construct(StudentAuthService $studentAuthService)
    {
        $this->studentAuthService = $studentAuthService;
    }

    public function phone(StudentLoginPhoneRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $validated = $request->validated();

        return $this->studentAuthService->phone($validated['phone_number']);
    }

    public function verify(StudentLoginRequest $request)
    {
        return $this->studentAuthService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->studentAuthService->logout($request->user());
    }
}
