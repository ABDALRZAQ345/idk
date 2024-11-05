<?php

namespace App\Http\Requests\Users;

use App\Exceptions\BadCredentialsException;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class UserLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'numeric', 'digits:10', 'exists:users,phone_number'],
            'password' => ['required', Password::defaults()],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \App\Exceptions\BadCredentialsException
     */
    public function authenticate(): User
    {
        $user = User::where('phone_number', $this->phone_number)->first();

        if (!Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->ip());

            throw new BadCredentialsException;
        }

        return $user;
    }

}
