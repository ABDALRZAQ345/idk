<?php

namespace App\Services\Students;

use App\Http\Requests\Students\StudentLoginRequest;
use App\Http\Requests\Students\StudentRegisterRequest;
use App\Models\Student;
use App\Services\VerificationCodeService;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class StudentAuthService
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
     * Send a verification code to the phone number
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
     * Verify phone number and code then register the student
     * @param \App\Http\Requests\Students\StudentRegisterRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(StudentRegisterRequest $request)
    {
        $validated = $request->validated();

        $this->verificationCodeService->verify($validated['phone_number'], $validated['code']);

        $student = Student::create([
            'name' => $validated['name'],
            'birth_date' => $validated['birth_date'],
            'phone_number' => $validated['phone_number'],
        ]);

        $this->verificationCodeService->delete($validated['phone_number']);

        $token = $student->createToken('api_token')->plainTextToken;

        RateLimiter::clear($request->ip());

        return response()->json([
            'message' => 'Student registered successfully',
            'student_id' => $student->id,
            'access_token' => $token,
            'toke_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    /**
     * Verify phone number and code then login the student
     * @param \App\Http\Requests\Students\StudentLoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(StudentLoginRequest $request)
    {
        $validated = $request->validated();

        $this->verificationCodeService->verify($validated['phone_number'], $validated['code']);

        $student = Student::where(
            'phone_number',
            $validated['phone_number']
        )->firstOrFail();

        $this->verificationCodeService->delete($validated['phone_number']);

        $token = $student->createToken('api_token')->plainTextToken;

        RateLimiter::clear($request->ip());

        return response()->json([
            'message' => 'Student logged in successfully',
            'student_id' => $student->id,
            'access_token' => $token,
            'token_type' => "Bearer",
        ]);
    }

    /**
     * Delete current access token
     * @param \App\Models\Student $student
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(Student $student)
    {
        $student->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Student logged out successfully',
        ]);
    }
}