<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Students\StudentRegisterPhoneRequest;
use App\Http\Requests\Students\StudentRegisterRequest;
use App\Services\Students\StudentAuthService;

class StudentRegisterController extends Controller
{
    private StudentAuthService $studentAuthService;

    public function __construct(StudentAuthService $studentAuthService)
    {
        $this->studentAuthService = $studentAuthService;
    }

    public function phone(StudentRegisterPhoneRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $validated = $request->validated();

        return $this->studentAuthService->phone($validated['phone_number']);
    }

    public function register(StudentRegisterRequest $request)
    {
        return $this->studentAuthService->register($request);
    }
}
