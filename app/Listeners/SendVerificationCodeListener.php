<?php

namespace App\Listeners;

use App\Events\SendVerificationCode;
use App\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;


class SendVerificationCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendVerificationCode $event): void
    {
        // TODO: Send verification code
        VerificationCode::where(
            'phone_number',
            $event->phone_number
        )->delete();

        $code = random_int(100000, 999999);

        VerificationCode::create([
            'phone_number' => $event->phone_number,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(30),
        ]);

        //Log::channel('verification_code')->info($code);
    }
}
