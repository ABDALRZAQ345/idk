<?php

namespace App\Http\Requests\Users;

use App\Models\UnregisteredUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
                'exists:verification_codes,phone_number',
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
                        $fail('The ' . $attribute . ' already exists');
                    }
                },
            ],
            'code' => ['required', 'numeric', 'digits:6'],
            'role' => ['required', 'integer', 'min:1', 'exists:roles,id'],
        ];
    }
}
