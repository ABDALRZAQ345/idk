<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Recitation\PageRecitationController;
use App\Http\Controllers\Recitation\SectionRecitationController;
use App\Http\Controllers\Recitation\SurahRecitationController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->group(function () {

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/students/{student}/page_recitations',[PageRecitationController::class,'store'])->name('page_recitation.store');
        Route::get('/mosques/{mosque}/students/{student}/page_recitations',[PageRecitationController::class,'index'])->name('page_recitation.index');
        Route::delete('/students/{student}/page_recitations/{page_recitation}',[PageRecitationController::class,'delete'])->name('page_recitation.delete');
        Route::put('/students/{student}/page_recitations/{page_recitation}',[PageRecitationController::class,'update'])->name('page_recitation.update');


        Route::post('/students/{student}/surah_recitations',[SurahRecitationController::class,'store'])->name('surah_recitation.store');
        Route::get('/mosques/{mosque}/students/{student}/surah_recitations',[SurahRecitationController::class,'index'])->name('surah_recitation.index');
        Route::delete('/students/{student}/surah_recitations/{surah_recitation}',[SurahRecitationController::class,'delete'])->name('surah_recitation.delete');
        Route::put('/students/{student}/surah_recitations/{surah_recitation}',[SurahRecitationController::class,'update'])->name('surah_recitation.update');


        Route::post('/students/{student}/section_recitations',[SectionRecitationController::class,'store'])->name('section_recitation.store');
        Route::get('/mosques/{mosque}/students/{student}/section_recitations',[SectionRecitationController::class,'index'])->name('section_recitation.index');
        Route::delete('/students/{student}/section_recitations/{section_recitation}',[SectionRecitationController::class,'delete'])->name('section_recitation.delete');
        Route::put('/students/{student}/section_recitations/{section_recitation}',[SectionRecitationController::class,'update'])->name('section_recitation.update');

        Route::get('/groups',[GroupController::class,'index'])->name('group.index');
        Route::post('/groups',[GroupController::class,'store'])->name('group.store');
        Route::delete('/groups/{group}',[GroupController::class,'delete'])->name('group.delete');



    });

    Route::post('/register',[AuthController::class,'register'])->name('auth.register');
});
