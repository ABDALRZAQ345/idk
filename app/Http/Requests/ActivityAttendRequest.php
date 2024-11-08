<?php

namespace App\Http\Requests;

use App\Rules\StudentBelongsToSameMosque;
use App\Rules\StudentHasActivity;
use Illuminate\Foundation\Http\FormRequest;

class ActivityAttendRequest extends FormRequest
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
            'students.*' => ['integer', 'exists:students,id', new StudentBelongsToSameMosque, new StudentHasActivity($this->activity)],
        ];
    }

    public function getActivity()
    {
        return $this->input('activity');
    }
}
