<?php

use App\Http\Controllers\UnregisteredUserController;

Route::get('/csrf-token', function () {
    return response()->json([
        'csrfToken' => csrf_token(),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::name('admin.')->prefix('/admin')->group(function () {

        Route::name('user.')->prefix('/users')->group(function () {
            
            Route::name('register.')->prefix('/register')->group(function () {

                Route::post('/phone', [UnregisteredUserController::class, 'phone'])
                    ->name('phone');

                Route::post('/', [UnregisteredUserController::class, 'store'])
                    ->name('store');
            });

            Route::delete('/{id}', [UnregisteredUserController::class, 'destroy'])
                ->name('destroy');
        });
    });

});
