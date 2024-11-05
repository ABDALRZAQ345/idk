<?php

namespace App\Http\Controllers;

use App\Http\Requests\Phones\PhoneVerificationRequest;
use App\Services\VerificationCodeService;

class VerificationCodeController extends Controller
{

    private VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(PhoneVerificationRequest $request)
    {
        $validated = $request->validated();

        $this->verificationCodeService->verify($validated['phone_number'], $validated['code']);

        return response()->json([
            'message' => 'Verified successfully',
        ]);
    }
}
