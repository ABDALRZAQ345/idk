<?php

namespace App\Services;

use App\Events\SendVerificationCode;
use App\Exceptions\VerificationCodeException;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;

class VerificationCodeService
{

    /**
     * Send verification code
     * @param string $phoneNumber
     * @return void
     */
    public function send(string $phoneNumber)
    {
        SendVerificationCode::dispatch($phoneNumber);
    }

    /**
     * Check if code belongs to phone number and not expired
     * @param string $phoneNumber
     * @param string $code
     * @throws \App\Exceptions\VerificationCodeException
     * @return void
     */
    public function verify(string $phoneNumber, string $code)
    {
        $verificationCode = VerificationCode::where(
            'phone_number',
            $phoneNumber
        )->first();

        if (!$verificationCode || !Hash::check($code, $verificationCode->code)) {
            throw new VerificationCodeException;
        }

        if (now() >= $verificationCode->expires_at) {
            throw new VerificationCodeException('Expired code');
        }
    }

    /**
     * Delete the verification code
     * @param string $phoneNumber
     * @throws \App\Exceptions\VerificationCodeException
     * @return void
     */
    public function delete(string $phoneNumber)
    {
        $verificationCode = VerificationCode::where(
            'phone_number',
            $phoneNumber
        )->first();

        if (!$verificationCode) {
            throw new VerificationCodeException('Invalid phone number');
        }

        $verificationCode->delete();
    }
}