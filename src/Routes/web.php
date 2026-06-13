<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads\Index as CrmLeadsIndex;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers\Index as CrmCustomersIndex;
use Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities\Index as CrmOppsIndex;

Route::middleware(['web', 'auth', 'role:super-admin|developer|admin|employee', 'license'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/crm/leads', CrmLeadsIndex::class)->name('admin.crm.leads');
        Route::get('/crm/customers', CrmCustomersIndex::class)->name('admin.crm.customers');
        Route::get('/crm/opportunities', CrmOppsIndex::class)->name('admin.crm.opportunities');
    });
