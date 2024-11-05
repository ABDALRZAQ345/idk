<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
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
                'exists:verification_codes,phone_number',
                'exists:students,phone_number',
            ],
            'code' => ['required', 'numeric', 'digits:6'],
        ];
    }
}
