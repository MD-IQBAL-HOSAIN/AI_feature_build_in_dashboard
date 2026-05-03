<?php

use App\Http\Controllers\Web\Backend\VersionPreviewController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/v1');

Route::prefix('v1')->group(base_path('routes/v1/backend.php'));
Route::prefix('v2')->group(base_path('routes/v2/backend.php'));

/*
|--------------------------------------------------------------------------
| Future Version Fallback
|--------------------------------------------------------------------------
|
| Existing versioned routes are registered first. If a route is missing for
| V2+ (for example /admin/v3/language), this fallback shows the preview page
| instead of returning a backend 404.
|
*/
Route::any('{version}/{any?}', [VersionPreviewController::class, 'show'])
    ->where([
        'version' => 'v(?:[2-9]|[1-9][0-9]+)',
        'any' => '.*',
    ]);
