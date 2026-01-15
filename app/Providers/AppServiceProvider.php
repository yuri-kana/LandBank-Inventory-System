<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
    public function boot()
    {
        // Register blade components with correct paths
        Blade::component('components.analytics.usage-patterns', 'analytics.usage-patterns');
        Blade::component('components.analytics.depletion-rate', 'analytics.depletion-rate');
        Blade::component('components.analytics.restock-management', 'analytics.restock-management');
        Blade::component('components.analytics.inventory-records', 'analytics.inventory-records');
        Blade::component('components.analytics.modals', 'analytics.modals');
        Blade::component('components.team-member-dashboard', 'team-member-dashboard');
    }
}