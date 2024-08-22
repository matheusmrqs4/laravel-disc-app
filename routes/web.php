<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GuildController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('guilds.index');
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
    Route::put('/guilds/{guild}', [GuildController::class, 'update'])->name('guilds.update');
    Route::delete('/guilds/{guild}', [GuildController::class, 'destroy'])->name('guilds.destroy');

    Route::get('/guilds/{guild}/channels/create', [ChannelController::class, 'create'])->name('channels.create');
    Route::post('/guilds/{guild}/channels', [ChannelController::class, 'store'])->name('channels.store');
    Route::get('/guilds/{guild}/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
    Route::delete('/guilds/{guild}/channel/{channel}', [ChannelController::class, 'destroy'])->name('channels.delete');
    Route::get('/guilds/{guild}/channels/{channel}/edit', [ChannelController::class, 'edit'])->name('channels.edit');
    Route::put('/guilds/{guild}/channels/{channel}/update', [ChannelController::class, 'update'])->name('channels.update');

    Route::post('/guilds/{guild}/channels/{channel}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/guilds/{guild}/channels/{channel}/messages/{message}', [MessageController::class, 'destroy'])->name('messages.delete');
});
