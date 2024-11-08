<?php

namespace App\Rules;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * check that user has no group in mosque
 */
class StudentHasActivity implements Rule
{
    protected $activity;

    public function __construct($activity)
    {
        $this->activity = $activity;
    }

    public function passes($attribute, $value): bool
    {
        $user = Auth::user();
        $student = Student::find($value);

        $group = $student->group($user->mosque->id);
        $activity = $this->activity;

        return $group != null && $activity->groups()->where('groups.id', $group->id)->exists();
    }

    public function message(): string
    {
        return 'The student is not a member of that activity ';
    }
}
