<?php

namespace App\Rules;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * check that user has no group in mosque
 */
class StudentHasLesson implements Rule
{
    protected $lesson;

    public function __construct($lesson)
    {
    $this->lesson = $lesson;
    }

    public function passes($attribute, $value): bool
    {
        $user = Auth::user();
        $student = Student::find($value);

        $group = $student->group($user->mosque->id);
        $lesson = $this->lesson;

        return $group != null && $lesson->groups()->where('groups.id', $group->id)->exists();
    }

    public function message(): string
    {
        return 'The student is not a member of that lesson ';
    }
}
