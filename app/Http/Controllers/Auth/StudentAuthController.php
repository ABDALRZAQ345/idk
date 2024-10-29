<?php

namespace App\Http\Controllers\Auth;

use App\Events\Students\StudentRegistered;
use App\Events\Students\StudentSendConfirmationCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Students\StudentLoginRequest;
use App\Http\Requests\Students\StudentRegisterRequest;
use App\Http\Requests\Students\StudentSendConfirmationCodeRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentAuthController extends Controller
{
    public function register(StudentRegisterRequest $request)
    {
        $validated = $request->validated();

        $savedStudent = Student::create($validated);

        StudentRegistered::dispatch($savedStudent);

        return response()->json([
            'message' => 'New student created successfully!',
        ], Response::HTTP_CREATED);
    }

    public function sendConfirmationCode(StudentSendConfirmationCodeRequest $request)
    {
        $validated = $request->validated();

        $student = Student::where('phone_number', '=', $validated['phone_number'])
            ->firstOrFail();

        $student->confirmation_code?->delete();

        StudentSendConfirmationCode::dispatch($student);

        return response()->json([
            'message' => 'A confirmation code has been sent to your phone number.',
        ]);
    }

    public function login(StudentLoginRequest $request)
    {
        $validated = $request->validated();

        $student = Student::where('phone_number', '=', $validated['phone_number'])
            ->firstOrFail();

        $confirmation_code = $student->confirmation_code;

        if (!$confirmation_code || $confirmation_code->code !== $validated['code']) {
            return response()->json([
                'message' => 'Invalid code',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ((now() >= $confirmation_code->expires_at)) {
            return response()->json([
                'message' => 'Expired code',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $confirmation_code->delete();

        $token = $student->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'student_id' => $student->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
