<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login.post');
Route::get('/register', [AuthenticationController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register.post');
Route::middleware('auth:web')->post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
