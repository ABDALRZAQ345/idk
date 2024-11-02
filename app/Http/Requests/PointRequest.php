<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointRequest extends FormRequest
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
            'surah_points' => ['required', 'array', 'size:30'],
            'surah_points.*.points' => ['required', 'integer'],
            'section_points' => ['required', 'integer'],
            'page_points' => ['required', 'integer'],
        ];
    }
}
