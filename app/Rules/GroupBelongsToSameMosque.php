<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class GroupBelongsToSameMosque implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Auth::user()->mosque->groups()->find($value) != null;
    }

    public function message(): string
    {
        return 'invalid group_id';
    }
}
