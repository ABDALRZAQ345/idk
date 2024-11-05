<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

/**
 * check that two users belongs to the same mosque
 */
class HasNoGroup implements Rule
{
    public function passes($attribute, $value): bool
    {

        $user = User::find($value);

        return $user->group == null;
    }

    public function message(): string
    {
        return 'The user is already has a group ';
    }
}
