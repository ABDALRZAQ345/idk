<?php

namespace App\Http\Requests\UnregisteredUsers;

use App\Exceptions\TooManyRequestsException;
use App\Models\UnregisteredUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;

class UnregisteredUserPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: only the manager should be able to do this
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
                function ($attribute, $value, $fail) {
                    $existsInUsers = User::where(
                        'phone_number',
                        $value
                    )->exists();

                    $existsInUnregisteredUser = UnregisteredUser::where(
                        'phone_number',
                        $value
                    )->exists();

                    if ($existsInUsers || $existsInUnregisteredUser) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' already exists');
                    }
                },
            ]
        ];
    }

    /**
     * Ensure the register phone request is not rate limited.
     *
     * @throws \App\Exceptions\TooManyRequestsException;
     */
    public function ensureIsNotRateLimited()
    {
        $ip = $this->ip();

        $dailyKey = $ip . '|daily';
        $thirtyMinutesKey = $ip . '|thirty_minutes';

        // Check for daily limit
        if (RateLimiter::tooManyAttempts($dailyKey, 5)) {
            throw new TooManyRequestsException(
                'You reached your daily limit. Try again tomorrow.'
            );
        }

        // Check for 30-minute limit
        if (RateLimiter::tooManyAttempts($thirtyMinutesKey, 1)) {
            $seconds = RateLimiter::availableIn($thirtyMinutesKey);
            $minutes = ceil($seconds / 60);

            throw new TooManyRequestsException(
                'Too many requests. Try again in ' . $minutes . ' minute(s).'
            );
        }

        // Allow the request and increment the count
        RateLimiter::hit($dailyKey, 24 * 60 * 60);
        RateLimiter::hit($thirtyMinutesKey, 30 * 60);
    }
}
