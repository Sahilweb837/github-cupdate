<?php

use App\Http\Controllers\CupdateController;
use Illuminate\Support\Facades\Route;

Route::controller(CupdateController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/how-it-works', 'howItWorks')->name('how-it-works');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'submitContact')->name('contact.submit');

    Route::get('/login', 'login')->name('login');
    Route::get('/join', 'join')->name('join');
    Route::post('/auth', 'authenticate')->name('auth.submit');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/privacy', fn () => app(CupdateController::class)->legal('privacy'))->name('privacy');
    Route::get('/terms', fn () => app(CupdateController::class)->legal('terms'))->name('terms');
    Route::get('/safety', fn () => app(CupdateController::class)->legal('safety'))->name('safety');

    Route::prefix('app')->name('app.')->group(function () {
        Route::get('/discover', 'discover')->name('discover');
        Route::get('/radar', 'radar')->name('radar');
        Route::get('/messages', 'messages')->name('messages');
        Route::get('/dates', 'dates')->name('dates');
        Route::get('/profile', 'profile')->name('profile');
    });
});
