<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'birth_date' => ['required', 'date', 'before_or_equal:'.now()->subYears(5)],
            'phone_number' => ['required', 'numeric', 'digits:10', 'unique:students,phone_number'],
            'code' => ['required', 'numeric', 'digits:6'],
        ];
    }
}
