<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Group\GroupStudents;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\Recitation\PageRecitationController;
use App\Http\Controllers\Recitation\SectionRecitationController;
use App\Http\Controllers\Recitation\SurahRecitationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPointController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TimeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->group(function () {

    Route::group(['middleware' => ['auth:sanctum', 'auth.type:user']], function () {

        Route::group([], function () {
            Route::post('/students/{student}/page_recitations', [PageRecitationController::class, 'store'])->middleware('permission:recitation.store')->name('page_recitation.store');
            Route::get('/students/{student}/page_recitations', [PageRecitationController::class, 'index'])->middleware('permission:recitation.read')->name('page_recitation.index');
            Route::delete('/students/{student}/page_recitations/{page_recitation}', [PageRecitationController::class, 'delete'])->middleware('permission:recitation.delete')->name('page_recitation.delete');
            Route::put('/students/{student}/page_recitations/{page_recitation}', [PageRecitationController::class, 'update'])->middleware('permission:recitation.update')->name('page_recitation.update');

            Route::post('/students/{student}/surah_recitations', [SurahRecitationController::class, 'store'])->middleware('permission:recitation.store')->name('surah_recitation.store');
            Route::get('/students/{student}/surah_recitations', [SurahRecitationController::class, 'index'])->middleware('permission:recitation.read')->name('surah_recitation.index');
            Route::delete('/students/{student}/surah_recitations/{surah_recitation}', [SurahRecitationController::class, 'delete'])->middleware('permission:recitation.delete')->name('surah_recitation.delete');
            Route::put('/students/{student}/surah_recitations/{surah_recitation}', [SurahRecitationController::class, 'update'])->middleware('permission:recitation.update')->name('surah_recitation.update');

            Route::post('/students/{student}/section_recitations', [SectionRecitationController::class, 'store'])->middleware('permission:recitation.store')->name('section_recitation.store');
            Route::get('/students/{student}/section_recitations', [SectionRecitationController::class, 'index'])->middleware('permission:recitation.read')->name('section_recitation.index');
            Route::delete('/students/{student}/section_recitations/{section_recitation}', [SectionRecitationController::class, 'delete'])->middleware('permission:recitation.delete')->name('section_recitation.delete');
            Route::put('/students/{student}/section_recitations/{section_recitation}', [SectionRecitationController::class, 'update'])->middleware('permission:recitation.update')->name('section_recitation.update');

        });

        Route::group([], function () {
            ////
            Route::get('/groups', [GroupController::class, 'index'])->name('group.index');
            Route::post('/groups', [GroupController::class, 'store'])->middleware('permission:groups.store')->name('group.store');
            Route::delete('/groups/{group}', [GroupController::class, 'delete'])->middleware('permission:groups.delete')->name('group.delete');
            Route::put('/groups/{group}', [GroupController::class, 'update'])->middleware('permission:groups.update')->name('group.update');
            ///
            Route::get('/groups/{group}/students', [GroupStudents::class, 'index'])->middleware('permission:group_students.read')->name('group.students.index');
            Route::post('/groups/{group}/students', [GroupStudents::class, 'store'])->middleware('permission:group_students.store')->name('group.students.store');
            Route::get('/groups/{group}/students/{student}', [GroupStudents::class, 'show'])->middleware('permission:group_students.read')->name('group.students.show');
            Route::delete('/groups/{group}/students/{student}', [GroupStudents::class, 'delete'])->middleware('permission:group_students.delete')->name('group.students.delete');
            Route::put('/groups/{group}/students/{student}', [GroupStudents::class, 'update'])->middleware('permission:group_students.update')->name('group.students.update');

        });
        Route::group([], function () {
            Route::get('/students', [StudentController::class, 'index'])->middleware('permission:students.read')->name('student.index');
            Route::get('/students/{student}', [StudentController::class, 'show'])->middleware('permission:students.read')->name('students.show');
            Route::delete('/students/{student}', [StudentController::class, 'delete'])->middleware('permission:students.delete')->name('students.delete');
        });

        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

        Route::group([], function () {
            Route::get('/points', [PointController::class, 'index'])->middleware('permission:points.read')->name('points_details.index');
            Route::put('/points', [PointController::class, 'update'])->middleware('permission:points.update')->name('points.update');
            Route::delete('/points', [PointController::class, 'delete'])->middleware('permission:points.delete')->name('points.delete');
            Route::post('/students/{student}/points', [StudentPointController::class, 'store'])->middleware('permission:student_points.store')->name('points.store');
            Route::get('/students/{student}/points', [StudentPointController::class, 'index'])->middleware('permission:student_points.read')->name('points.index');
            Route::get('/students/{student}/points/{point}', [StudentPointController::class, 'show'])->middleware('permission:student_points.read')->name('points.show');
            // Route::delete('/students/{student}/points/{point}', [StudentPointController::class, 'delete'])->middleware('permission:student_points.delete')->name('points.delete');
            // Route::put('/students/{student}/points/{point}', [StudentPointController::class, 'update'])->middleware('permission:student_points.update')->name('points.update');
        });
        Route::group([],function (){

           Route::get('/activities',[ActivityController::class, 'index'])->middleware('permission:activity.read')->name('activities.index');
           Route::post('/activities',[ActivityController::class, 'store'])->middleware('permission:activity.store')->name('activities.store');
           Route::get('/activities/{activity}',[ActivityController::class, 'show'])->middleware('permission:activity.read')->name('activities.show');
           Route::post('/activities/{activity}/cancel',[ActivityController::class, 'cancel'])->middleware('permission:activity.cancel')->name('activities.delete');
        });

    });

    Route::group(['middleware' => ['auth:sanctum', 'auth.type:student']], function () {
        Route::group(['prefix' => '/mosques/{mosque}'], function () {
            Route::get('/me', [StudentProfileController::class, 'index']);
            Route::get('/me/page_recitations', [StudentProfileController::class, 'pageRecitations']);
            Route::get('/me/surah_recitations', [StudentProfileController::class, 'surahRecitations']);
            Route::get('/me/section_recitations', [StudentProfileController::class, 'sectionRecitations']);
            Route::get('/me/points', [StudentProfileController::class, 'points']);
            Route::get('/me/activities', [StudentProfileController::class, 'activities']);
        });

    });

    Route::prefix('students')->group(function () {
        Route::post('/register', [StudentAuthController::class, 'register'])->middleware('throttle:api')->name('register');

        Route::post('/login', [StudentAuthController::class, 'login'])
            ->name('login');

        Route::post('/send-confirmation-code', [StudentAuthController::class, 'sendConfirmationCode'])
            ->middleware('throttle:send_confirmation_code')
            ->name('send.confirmation.code');

        Route::post('/logout', [StudentAuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');
    });

    Route::get('/time',[TimeController::class,'time']);
    Route::post('/s', [AuthController::class, 'register'])->name('auth.register');
});
