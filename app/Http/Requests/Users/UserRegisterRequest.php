<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => ['required', 'numeric', 'digits:10', 'exists:unregistered_users,phone_number'],
            'code' => ['required', 'numeric', 'digits:6'],
        ];
    }
}
