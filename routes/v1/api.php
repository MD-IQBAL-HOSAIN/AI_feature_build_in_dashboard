<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\DynamicPageController;
use App\Http\Controllers\API\V1\FcmTokenController;
use App\Http\Controllers\API\V1\LanguageApiController;
use App\Http\Controllers\API\V1\SocialLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| without jwt api middleware
|--------------------------------------------------------------------------
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/forgot', 'forgotPassword');
    Route::post('/password/reset', 'resetPassword');
    Route::post('/password/resend-otp', 'resendOtp');
    Route::post('/password/verify-otp', 'verifyOtp');
});

// Continue with google,facebook and apple login
Route::controller(SocialLoginController::class)->group(function () {
    Route::post('/social/login', 'socialLogin');
    Route::post('/guest/login', 'guestLogin');
});

// Public-Access (language list endpoint.)
Route::controller(LanguageApiController::class)->group(function () {
    Route::get('/languages', 'index');
});

/*
|--------------------------------------------------------------------------
| with jwt middlware api
|--------------------------------------------------------------------------
|
*/
// Throttle: max 60 requests per minute
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('/profile', 'profile');
        Route::delete('/delete-account', 'deleteAccount');
        Route::post('/profile/update/user', 'ProfileUpdate');
        Route::post('/password/update/user', 'ChangePassword');
        Route::get('/user/profile/get', 'profileRetrieval');
    });

    // Routes for Dynamic Page
    Route::controller(DynamicPageController::class)->group(function () {
        Route::get('dynamic-page', 'index');
        Route::get('faq-list', 'faq');
    });

    // Route for Fcm token store
    Route::controller(FcmTokenController::class)->group(function () {
        Route::post('/fcm/token/store', 'store');
        Route::delete('/fcm/token/delete/{id}', 'destroy');
    });

});

// no need now. when frontend needed then we will implement it
require_once __DIR__.'/frontend/dashboard.php';
