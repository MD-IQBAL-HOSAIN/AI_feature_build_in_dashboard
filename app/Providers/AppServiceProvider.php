<?php

namespace App\Providers;

use App\Interfaces\DynamicPageServiceInterface;
use App\Interfaces\FaqServiceInterface;
use App\Interfaces\LanguageServiceInterface;
use App\Interfaces\SystemUserServiceInterface;
use App\Models\Setting;
use App\Services\DynamicPageService;
use App\Services\FaqService;
use App\Services\LanguageService;
use App\Services\SystemUserService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DynamicPageServiceInterface::class, DynamicPageService::class);
        $this->app->bind(FaqServiceInterface::class, FaqService::class);
        $this->app->bind(LanguageServiceInterface::class, LanguageService::class);
        $this->app->bind(SystemUserServiceInterface::class, SystemUserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            View::share('settings', null);

            return;
        }

        /**
         * Share the settings model globally across all views.
         * For performance reason (avoid repeated queries)
         * This query is executed once during the request lifecycle.
         * It prevents repeated database queries that can happen when using
         * wildcard view composers for nested/partial Blade renders.
         */
        try {
            if (Schema::hasTable('settings')) {
                View::share('settings', Setting::first());
            } else {
                View::share('settings', null);
            }
        } catch (Throwable) {
            View::share('settings', null);
        }
    }

    /*
    //if you want to force https in production
    public function boot(): void
    {

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            request()->server->set('HTTPS', request()->header('X-Forwarded-Proto', 'https') == 'https' ? 'on' : 'off');
        }
    }
    */
}

