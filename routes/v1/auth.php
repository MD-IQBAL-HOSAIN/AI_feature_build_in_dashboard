<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\V1\Auth\AuthController;
use App\Http\Controllers\Web\Backend\V1\Auth\PasswordResetController;

Route::get('login', [AuthController::class,'getLogin'])->name('login');

Route::group(['as'=> 'auth.'], function () {

    Route::get('signup', [AuthController::class,'getSignUp'])->name('signup.get');
    Route::post('signup', [AuthController::class,'signup'])->name('signup.post');
    
    Route::post('login', [AuthController::class,'login'])->name('login.post');

    Route::get('reset-password-link', [PasswordResetController::class,'create'])->name('reset.link.get');
    Route::post('reset-password-link', [PasswordResetController::class,'submitMail'])->name('reset.link.post');

    // Route::get('otp-link', [PasswordResetController::class,'get'])->name('otp.get');
    Route::post('otp-link', [PasswordResetController::class,'submitOtp'])->name('otp.post');
    Route::post('finish-reset-password', [PasswordResetController::class, 'reset'])->name('reset.finish');

    Route::group(['middleware'=> 'admin.auth'], function () {
        Route::post('logout', [AuthController::class,'logout'])->name('logout.post');
        Route::get('reset-password', [AuthController::class,'getResetPasswordForm'])->name('reset.get');
        Route::post('reset-password', [AuthController::class,'resetPassword'])->name('reset.post');

    });

});



