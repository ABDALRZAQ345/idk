<?php

namespace App\Http\Requests\Group;

use App\Rules\StudentBelongsToSameMosque;
use App\Rules\StudentNotBelongsToGroupInMosque;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GroupStudentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'students' => ['required', 'array'],
            'students.*' => ['required', new StudentBelongsToSameMosque, new StudentNotBelongsToGroupInMosque(Auth::user()->mosque->id)],
        ];
    }
}
