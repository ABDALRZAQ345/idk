<?php

use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Recitation\PageRecitationController;
use App\Http\Controllers\Recitation\SectionRecitationController;
use App\Http\Controllers\Recitation\SurahRecitationController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\VerificationCodeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->group(function () {

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/students/{student}/page_recitations', [PageRecitationController::class, 'store'])->name('page_recitation.store');
        Route::get('/mosques/{mosque}/students/{student}/page_recitations', [PageRecitationController::class, 'index'])->name('page_recitation.index');
        Route::delete('/students/{student}/page_recitations/{page_recitation}', [PageRecitationController::class, 'delete'])->name('page_recitation.delete');
        Route::put('/students/{student}/page_recitations/{page_recitation}', [PageRecitationController::class, 'update'])->name('page_recitation.update');





        Route::post('/students/{student}/surah_recitations', [SurahRecitationController::class, 'store'])->name('surah_recitation.store');
        Route::get('/mosques/{mosque}/students/{student}/surah_recitations', [SurahRecitationController::class, 'index'])->name('surah_recitation.index');
        Route::delete('/students/{student}/surah_recitations/{surah_recitation}', [SurahRecitationController::class, 'delete'])->name('surah_recitation.delete');
        Route::put('/students/{student}/surah_recitations/{surah_recitation}', [SurahRecitationController::class, 'update'])->name('surah_recitation.update');


        Route::post('/students/{student}/section_recitations', [SectionRecitationController::class, 'store'])->name('section_recitation.store');
        Route::get('/mosques/{mosque}/students/{student}/section_recitations', [SectionRecitationController::class, 'index'])->name('section_recitation.index');
        Route::delete('/students/{student}/section_recitations/{section_recitation}', [SectionRecitationController::class, 'delete'])->name('section_recitation.delete');
        Route::put('/students/{student}/section_recitations/{section_recitation}', [SectionRecitationController::class, 'update'])->name('section_recitation.update');

        Route::get('/groups', [GroupController::class, 'index'])->name('group.index');
        Route::post('/groups', [GroupController::class, 'store'])->name('group.store');
        Route::delete('/groups/{group}', [GroupController::class, 'delete'])->name('group.delete');



    });

    Route::name('student.')->prefix('/students')->group(function () {

        Route::name('register.')->prefix('/register')->group(function () {
            Route::post('/phone-number', [StudentRegisterController::class, 'phone'])
                ->name('phone');

            Route::post('/verification', VerificationCodeController::class)
                ->name('verify');

            Route::post('/personal-info', [StudentRegisterController::class, 'register'])
                ->name('register');
        });

        Route::name('login.')->prefix('/login')->group(function () {
            Route::post('/phone-number', [StudentLoginController::class, 'phone'])
                ->name('phone');

            Route::post('/verification', [StudentLoginController::class, 'verify'])
                ->name('verify');

        });

        Route::post('/logout', [StudentLoginController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');
    });

    Route::name('user.')->prefix('/users')->group(function () {

        Route::name('register.')->prefix('/register')->group(function () {

            Route::post('/phone-number', [UserAuthController::class, 'phone'])
                ->name('phone');

            Route::post('/verification', VerificationCodeController::class)
                ->name('verify');

            Route::post('/personal-info', [UserAuthController::class, 'register'])
                ->name('register');
        });

        Route::post('/login', [UserAuthController::class, 'login'])
            ->name('login');

        Route::post('/logout', [UserAuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');

    });
});
