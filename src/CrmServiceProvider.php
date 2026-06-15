<?php

namespace Dev3bdulrahman\Crm;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
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

        // Register Policies
        Gate::policy(\Dev3bdulrahman\Crm\Models\Lead::class, \Dev3bdulrahman\Crm\Policies\LeadPolicy::class);
        Gate::policy(\Dev3bdulrahman\Crm\Models\Customer::class, \Dev3bdulrahman\Crm\Policies\CustomerPolicy::class);
        Gate::policy(\Dev3bdulrahman\Crm\Models\Opportunity::class, \Dev3bdulrahman\Crm\Policies\OpportunityPolicy::class);

        // Register Events and Listeners
        Event::listen(\Dev3bdulrahman\Crm\Events\LeadCreated::class, \Dev3bdulrahman\Crm\Listeners\SendLeadNotification::class);
        Event::listen(\Dev3bdulrahman\Crm\Events\LeadConverted::class, \Dev3bdulrahman\Crm\Listeners\LogLeadConversion::class);
        // Event::listen(\Dev3bdulrahman\Crm\Events\CustomerCreated::class, ...); // TODO: Add listener for future extensibility

        // Register Livewire Components
        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('crm-leads-index', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Index::class);
            \Livewire\Livewire::component('crm-leads-create', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Create::class);
            \Livewire\Livewire::component('crm-leads-edit', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Edit::class);
            \Livewire\Livewire::component('crm-leads-show', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Show::class);
            \Livewire\Livewire::component('crm-customers-index', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Index::class);
            \Livewire\Livewire::component('crm-customers-create', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Create::class);
            \Livewire\Livewire::component('crm-customers-edit', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Edit::class);
            \Livewire\Livewire::component('crm-customers-show', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Show::class);
            \Livewire\Livewire::component('crm-opportunities-index', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Index::class);
            \Livewire\Livewire::component('crm-opportunities-create', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Create::class);
            \Livewire\Livewire::component('crm-opportunities-edit', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Edit::class);
            \Livewire\Livewire::component('crm-opportunities-show', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Show::class);
            \Livewire\Livewire::component('crm-pipeline-kanban', \Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Pipeline\Kanban::class);
        }
    }
}
