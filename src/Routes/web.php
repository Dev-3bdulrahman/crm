<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Index as CrmLeadsIndex;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Create as CrmLeadsCreate;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Show as CrmLeadsShow;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Edit as CrmLeadsEdit;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Index as CrmCustomersIndex;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Create as CrmCustomersCreate;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Show as CrmCustomersShow;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Edit as CrmCustomersEdit;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Index as CrmOppsIndex;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Create as CrmOppsCreate;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Show as CrmOppsShow;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Edit as CrmOppsEdit;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Pipeline\Kanban as CrmPipelineKanban;

Route::middleware(['web', 'auth', 'role:super-admin|developer|admin|employee', 'license'])
    ->prefix('admin')
    ->group(function () {
        // Leads routes
        Route::get('/crm/leads', CrmLeadsIndex::class)->name('admin.crm.leads');
        Route::get('/crm/leads/create', CrmLeadsCreate::class)->name('admin.crm.leads.create');
        Route::get('/crm/leads/{lead}', CrmLeadsShow::class)->name('admin.crm.leads.show');
        Route::get('/crm/leads/{lead}/edit', CrmLeadsEdit::class)->name('admin.crm.leads.edit');

        // Customers routes
        Route::get('/crm/customers', CrmCustomersIndex::class)->name('admin.crm.customers');
        Route::get('/crm/customers/create', CrmCustomersCreate::class)->name('admin.crm.customers.create');
        Route::get('/crm/customers/{customer}', CrmCustomersShow::class)->name('admin.crm.customers.show');
        Route::get('/crm/customers/{customer}/edit', CrmCustomersEdit::class)->name('admin.crm.customers.edit');

        // Opportunities routes
        Route::get('/crm/opportunities', CrmOppsIndex::class)->name('admin.crm.opportunities');
        Route::get('/crm/opportunities/create', CrmOppsCreate::class)->name('admin.crm.opportunities.create');
        Route::get('/crm/opportunities/{opportunity}', CrmOppsShow::class)->name('admin.crm.opportunities.show');
        Route::get('/crm/opportunities/{opportunity}/edit', CrmOppsEdit::class)->name('admin.crm.opportunities.edit');

        // Pipeline Kanban
        Route::get('/crm/pipeline/kanban', CrmPipelineKanban::class)->name('admin.crm.pipeline.kanban');
    });
