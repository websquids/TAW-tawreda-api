<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::get('/', function () {
  return view('welcome');
});

// Add this to routes/web.php or routes/api.php
Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('password/email', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');

Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
