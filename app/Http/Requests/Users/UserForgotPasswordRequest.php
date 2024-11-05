<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserForgotPasswordRequest extends FormRequest
{
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
                'exists:users,phone_number',
                'exists:verification_codes,phone_number',
            ],
            'code' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
