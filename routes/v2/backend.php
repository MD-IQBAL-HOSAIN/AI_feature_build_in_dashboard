<?php

use App\Http\Controllers\Web\Backend\V2\SystemUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| V2 Preview Routes
|--------------------------------------------------------------------------
|
| These routes are reserved for the next admin/backend version. For now we
| expose a preview page so the team can verify that the V2 namespace, routes,
| and views are wired correctly before real features are added.
|
*/


Route::resource('system-user', SystemUserController::class)
    ->except(['show']);
