<?php

use App\Models\Mosque;

Route::get('/', function () {
    $mosque = Mosque::first();

    $students = $mosque->students;

    $users = $mosque->users()->with('role')->get();

    $roles = $mosque->roles()->with('permissions')->get();

    $groups = $mosque->groups()->with('students')
        ->get();

    return response()->json([
        'mosque' => $mosque,
        'students' => $students,
        'users' => $users,
        'roles' => $roles,
        'groups' => $groups,
    ]);

});
