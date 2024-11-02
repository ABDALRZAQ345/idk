<?php

use App\Http\Controllers\UserController;
use App\Models\Mosque;

Route::get('/csrf-token', function () {
    return response()->json([
        'csrfToken' => csrf_token()
    ]);
});

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

Route::name('user.')->prefix('/users')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::name('admin.')->prefix('/admin')->group(function () {

            Route::post('/', [UserController::class, 'store'])
                ->name('store');

            Route::delete('/{id}', [UserController::class, 'destroy'])
                ->name('destroy');
        });

    });
});
