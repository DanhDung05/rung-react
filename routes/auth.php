<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Login/Logout dùng chung cho mọi role.
| Sau khi login → redirect theo role trong LoginController.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('dang-nhap', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('dang-nhap', [LoginController::class, 'login'])
        ->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('dang-xuat', [LoginController::class, 'logout'])
        ->name('logout');
});