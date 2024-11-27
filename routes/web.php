<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'xss'],function (){

});


Route::get('/csrf-token', function () {
    return response()->json([
        'csrfToken' => csrf_token(),
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

