<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::get('/login/redirect', [AuthController::class, 'login'])->name('login-redirect');
    Route::get('/login/callback', [AuthController::class, 'callback']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [PollController::class, 'index'])->name('poll.index');
    Route::get('/polls/{poll:slug}', [PollController::class, 'show'])->name('poll.show');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

