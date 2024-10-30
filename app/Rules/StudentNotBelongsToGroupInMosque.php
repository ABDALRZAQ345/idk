<?php

namespace App\Rules;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

/**
 * check that user has no group in mosque
 */
class StudentNotBelongsToGroupInMosque implements Rule
{
    protected $mosqueId;

    public function __construct($mosqueId)
    {
        $this->mosqueId = $mosqueId;
    }

    public function passes($attribute, $value): bool
    {

        $student = Student::find($value);

        return ! $student->hasGroup($this->mosqueId);
    }

    public function message(): string
    {
        return 'The student is already in a group ';
    }
}
