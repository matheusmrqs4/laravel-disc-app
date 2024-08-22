<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GuildController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login.post');
Route::get('/register', [AuthenticationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register.post');
Route::middleware('auth:web')->post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::middleware(['auth:web'])->group(function () {
    Route::get('/guilds', [GuildController::class, 'index'])->name('guilds.index');
    Route::get('/guilds/create', [GuildController::class, 'create'])->name('guilds.create');
    Route::post('/guilds', [GuildController::class, 'store'])->name('guilds.store');
    Route::get('/guilds/{guild}', [GuildController::class, 'show'])->name('guilds.show');
    Route::get('/guilds/{guild}/edit', [GuildController::class, 'edit'])->name('guilds.edit');
    Route::patch('/guilds/{guild}', [GuildController::class, 'update'])->name('guilds.update');
    Route::delete('/guilds/{guild}', [GuildController::class, 'destroy'])->name('guilds.destroy');
});
