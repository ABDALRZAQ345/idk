<?php

namespace App\Listeners;

use App\Events\Students\StudentRegistered;
use App\Events\Students\StudentSendConfirmationCode;
use Log;

class SendConfirmationCodeListener
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
    public function handle(StudentRegistered|StudentSendConfirmationCode $event): void
    {
        // TODO: Send confirmation code
        $confirmationCode = $event->student->confirmation_code()->create(
            [
                'code' => random_int(100000, 999999),
                'expires_at' => now()->addMinutes(30),
            ]
        );

        Log::channel('confirm_code')->info($confirmationCode->code);
    }
}
