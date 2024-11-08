<?php

namespace App\Http\Requests;

use App\Rules\GroupBelongsToSameMosque;
use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'groups' => ['required', 'array'],
            'groups.*' => ['integer', 'exists:groups,id', new GroupBelongsToSameMosque],
            'start_date' => ['required', 'date', 'date_format:Y-m-d H:i:s', 'after_or_equal:'.now()->format('Y-m-d H:i:s')],
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:300'],
            'type' => ['required', 'string','max:50'],
        ];
    }
}
