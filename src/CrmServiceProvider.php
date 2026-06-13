<?php

namespace Dev3bdulrahman\Crm;

use Illuminate\Support\ServiceProvider;

class CrmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge configuration if exists later
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        // Load package views
        $this->loadViewsFrom(__DIR__ . '/Views', 'crm');

        // Load package translations
        $this->loadTranslationsFrom(__DIR__ . '/Translations', 'crm');

        // Register Livewire Components
        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('crm-leads-index', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Index::class);
            \Livewire\Livewire::component('crm-customers-index', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Index::class);
            \Livewire\Livewire::component('crm-opportunities-index', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Index::class);
        }
    }
}
