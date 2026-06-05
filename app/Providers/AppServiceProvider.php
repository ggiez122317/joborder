<?php

namespace App\Providers;

use App\Services\PdsAdminService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('layouts.app', function ($view) {
            $user = auth()->user();

            $view->with('sidebarAdminCounts', $user?->isAdmin()
                ? Cache::remember('sidebar_counts', 60, fn () => app(PdsAdminService::class)->sidebarCounts())
                : ['imports' => 0, 'incomplete' => 0]);
        });
    }
}
