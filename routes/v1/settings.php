<?php

use App\Http\Controllers\Web\Backend\V1\Settings\MailController;
use App\Http\Controllers\Web\Backend\V1\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\V1\Settings\StripeSettingsController;
use App\Http\Controllers\Web\Backend\V1\Settings\SystemController;
use App\Http\Controllers\Web\Backend\V1\Settings\ThirdPartyApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Backend Settings Routes (v1)
|--------------------------------------------------------------------------
|
*/

Route::group(["prefix" => "settings", "as" => "settings."], function () {
    // Profile settings: view profile, update profile fields and upload assets
    Route::controller(ProfileController::class)->name('profile.')->group(function () {
        // Show the profile settings page
        Route::get('/', 'index')->name('index');

        // Upload user avatar (multipart/form-data expected)
        Route::post('upload-avatar', 'avatar')->name('avatar.upload');

        // Upload profile/banner image
        Route::post('upload-banner', 'banner')->name('banner.upload');

        // Update profile information (partial update allowed)
        Route::patch('update-profile', 'update')->name('update');
    });

    // System settings: application-wide configuration (read + update)
    Route::controller(SystemController::class)->prefix('system/')->name('system.')->group(function () {
        // List or show system settings
        Route::get('', 'index')->name('index');

        // Replace or update system settings
        Route::put('update', 'update')->name('update');
    });

    // Mail settings: configure SMTP, mail drivers, templates, etc.
    Route::controller(MailController::class)->prefix('mail/')->name('mail.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::patch('update', 'update')->name('update');
    });

    // Third-party API credentials and integration settings
    Route::controller(ThirdPartyApiController::class)->prefix('third-party-api/')->name('third-party-api.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::patch('update', 'update')->name('update');
    });

    // Payment provider settings (Stripe): view and update credentials
    Route::controller(StripeSettingsController::class)->prefix('payments/')->name('payments.stripe.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });
});
