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
     *
     * @return void
     */
    public function send(string $phoneNumber)
    {
        SendVerificationCode::dispatch($phoneNumber);
    }

    /**
     * Check if code belongs to phone number and not expired
     *
     * @return void
     *
     * @throws \App\Exceptions\VerificationCodeException
     */
    public function verify(string $phoneNumber, string $code)
    {
        $verificationCode = VerificationCode::where(
            'phone_number',
            $phoneNumber
        )->first();

        if (! $verificationCode || ! Hash::check($code, $verificationCode->code)) {
            throw new VerificationCodeException;
        }

        if (now() >= $verificationCode->expires_at) {
            throw new VerificationCodeException('Expired code');
        }
    }

    /**
     * Delete the verification code
     *
     * @return void
     *
     * @throws \App\Exceptions\VerificationCodeException
     */
    public function delete(string $phoneNumber)
    {
        $verificationCode = VerificationCode::where(
            'phone_number',
            $phoneNumber
        )->first();

        if (! $verificationCode) {
            throw new VerificationCodeException('Invalid phone number');
        }

        $verificationCode->delete();
    }
}
