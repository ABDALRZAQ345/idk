<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
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
            'start_date' => ['required', 'date','date_format:Y-m-d H:i:s','after_or_equal:today'],
            'duration' => ['required','integer'],
            'name' => ['required', 'string','max:25'],
            'description' => ['required', 'string','max:300'],
        ];
    }
}
