<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->group(function () {

    //    Route::middleware('guest')->group(function () {
    //
    //        Route::post('/login', [AuthController::class, 'login'])->name('login');
    //        Route::post('/register', [AuthController::class, 'register'])->name('register');
    //    });
    //
    //    Route::middleware('auth:sanctum')->group(function () {
    //        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    //        Route::put('/update', [AuthController::class, 'update'])->name('update');
    //        Route::delete('/delete_account', [AuthController::class, 'delete'])->middleware('email_verified')->name('delete-account');
    //    });

    Route::post('/register', [\App\Http\Controllers\Auth\StudentAuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Auth\StudentAuthController::class, 'login']);
    Route::get('/', function () {

        $student = Auth::user();

        return response()->json($student);

    })->middleware('auth:sanctum');

});
