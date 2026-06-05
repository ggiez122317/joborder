<?php

namespace App\Providers;

use App\Services\PdsAdminService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('layouts.app', function ($view) {
            $user = auth()->user();

            $view->with('sidebarAdminCounts', $user?->isAdmin()
                ? app(PdsAdminService::class)->sidebarCounts()
                : ['imports' => 0, 'incomplete' => 0]);
        });
    }
}
