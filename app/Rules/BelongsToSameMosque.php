<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BelongsToSameMosque implements Rule
{
    public function passes($attribute, $value)
    {
        // Add your validation logic here
        $user = User::find(\Auth::id());
        $second_user = User::find($value);

        return $user->mosque_id == $second_user->mosque_id;
    }

    public function message()
    {
        return 'The user is invalid ';
    }
}
