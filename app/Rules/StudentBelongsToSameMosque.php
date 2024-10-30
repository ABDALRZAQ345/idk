<?php

namespace App\Rules;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * check that student belongs to the same mosque of the user
 */
class StudentBelongsToSameMosque implements Rule
{
    protected $value;

    public function passes($attribute, $value): bool
    {
        $this->value = $value;
        $user = Auth::user();
        $student = Student::find($value);

        return $student != null && $student->mosques()->find($user->mosque->id) != null;
    }

    public function message(): string
    {
        return 'The student with id '.$this->value.' does not exist ';
    }
}
