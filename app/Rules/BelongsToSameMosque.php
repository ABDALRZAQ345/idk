<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * check that two users belongs to the same mosque
 */
class BelongsToSameMosque implements Rule
{
    public function passes($attribute, $value): bool
    {
        $user = Auth::user();
        $second_user = User::find($value);

        return $user->mosque_id == $second_user->mosque_id;
    }

    public function message(): string
    {
        return 'The user is invalid ';
    }
}
