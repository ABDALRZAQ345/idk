<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Group\GroupStudents;
use App\Http\Controllers\Recitation\PageRecitationController;
use App\Http\Controllers\Recitation\SectionRecitationController;
use App\Http\Controllers\Recitation\SurahRecitationController;
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

        Route::group([], function () {
            // crud for groups
            Route::get('/groups', [GroupController::class, 'index'])->name('group.index');
            Route::post('/groups', [GroupController::class, 'store'])->name('group.store');
            Route::delete('/groups/{group}', [GroupController::class, 'delete'])->name('group.delete');
            Route::put('/groups/{group}', [GroupController::class, 'update'])->name('group.update');
            // crud for students groups
            Route::get('/groups/{group}/students', [GroupStudents::class, 'index'])->name('group.students.index');
            Route::post('/groups/{group}/students', [GroupStudents::class, 'store'])->name('group.students.store');
            Route::get('/groups/{group}/students/{student}', [GroupStudents::class, 'show'])->name('group.students.show');
            Route::delete('/groups/{group}/students/{student}', [GroupStudents::class, 'delete'])->name('group.students.delete');
            Route::put('/groups/{group}/students/{student}', [GroupStudents::class, 'update'])->name('group.students.update');
        });

    });
    Route::prefix('students')->group(function () {
        Route::post('/register', [StudentAuthController::class, 'register'])
            ->middleware('throttle:api')
            ->name('register');

        Route::post('/login', [StudentAuthController::class, 'login'])
            ->name('login');

        Route::post('/send-confirmation-code', [StudentAuthController::class, 'sendConfirmationCode'])
            ->middleware('throttle:send_confirmation_code')
            ->name('send.confirmation.code');

        Route::post('/logout', [StudentAuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');
    });

    Route::post('/s', [AuthController::class, 'register'])->name('auth.register');
});
