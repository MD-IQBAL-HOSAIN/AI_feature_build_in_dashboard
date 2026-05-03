<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

//! This route is for the root URL and redirects to the login page.
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Protected Maintenance Routes
|--------------------------------------------------------------------------
| These routes are intended for controlled maintenance operations from a
| trusted environment. Access is protected by a shared token defined in
| MAINTENANCE_ROUTE_TOKEN and rate-limited to reduce abuse risk.
|
| Examples:
|   /migrate?token=YOUR_SECRET_TOKEN
|   /rollback?token=YOUR_SECRET_TOKEN&step=1
|   /seed?token=YOUR_SECRET_TOKEN
*/



//! throttle: max 5 requests per minute to prevent abuse. Adjust as needed.
Route::middleware('throttle:5,1')->group(function () {
    // Centralized token check for all maintenance endpoints.
    $isAuthorized = static function (Request $request): bool {
        $token = (string) $request->query('token', '');
        $expectedToken = (string) env('MAINTENANCE_ROUTE_TOKEN', '');

        return $expectedToken !== '' && hash_equals($expectedToken, $token);
    };

    //! Maintenance route: /migrate - Run database migrations.
    Route::get('/migrate', function (Request $request) use ($isAuthorized) {
        if (! $isAuthorized($request)) {
            abort(403, 'Unauthorized token.');
        }

        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Migration completed successfully.',
            'output' => Artisan::output(),
        ]);
    });

    //! Maintenance route: /rollback - Rollback database migrations.
    Route::get('/rollback', function (Request $request) use ($isAuthorized) {
        if (! $isAuthorized($request)) {
            abort(403, 'Unauthorized token.');
        }

        $step = (int) $request->query('step', 1);
        $step = $step > 0 ? $step : 1;

        Artisan::call('migrate:rollback', [
            '--step' => $step,
            '--force' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rollback completed successfully.',
            'step' => $step,
            'output' => Artisan::output(),
        ]);
    });

    //! Maintenance route: /seed - Run database seeders.
    Route::get('/seed', function (Request $request) use ($isAuthorized) {
        if (! $isAuthorized($request)) {
            abort(403, 'Unauthorized token.');
        }

        Artisan::call('db:seed', ['--force' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Seeder completed successfully.',
            'output' => Artisan::output(),
        ]);
    });

    //! Maintenance route: /migrate-fresh-seed - Drop all tables, migrate, and seed.
    Route::get('/migrate-fresh-seed', function (Request $request) use ($isAuthorized) {
        if (! $isAuthorized($request)) {
            abort(403, 'Unauthorized token.');
        }

        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fresh migration and seeding completed successfully.',
            'output' => Artisan::output(),
        ]);
    });
});

require_once __DIR__ . '/auth.php';


