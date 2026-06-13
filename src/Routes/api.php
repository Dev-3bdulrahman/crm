<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Crm\Http\Controllers\Api\LeadApiController;
use Dev3bdulrahman\Crm\Http\Controllers\Api\OpportunityApiController;
use Dev3bdulrahman\Crm\Http\Controllers\Api\CustomerApiController;

Route::prefix('api/v1/crm')->middleware(['api', 'auth'])->group(function () {

    // Leads Endpoints
    Route::get('leads', [LeadApiController::class, 'index'])->middleware('can:crm.leads.view');
    Route::post('leads', [LeadApiController::class, 'store'])->middleware('can:crm.leads.create');
    Route::get('leads/{id}', [LeadApiController::class, 'show'])->middleware('can:crm.leads.view');
    Route::put('leads/{id}', [LeadApiController::class, 'update'])->middleware('can:crm.leads.edit');
    Route::delete('leads/{id}', [LeadApiController::class, 'destroy'])->middleware('can:crm.leads.delete');
    Route::post('leads/{id}/convert', [LeadApiController::class, 'convert'])->middleware('can:crm.leads.convert');

    // Opportunities Endpoints
    Route::get('opportunities', [OpportunityApiController::class, 'index'])->middleware('can:crm.opportunities.view');
    Route::post('opportunities', [OpportunityApiController::class, 'store'])->middleware('can:crm.opportunities.create');
    Route::get('opportunities/{id}', [OpportunityApiController::class, 'show'])->middleware('can:crm.opportunities.view');
    Route::put('opportunities/{id}', [OpportunityApiController::class, 'update'])->middleware('can:crm.opportunities.edit');
    Route::delete('opportunities/{id}', [OpportunityApiController::class, 'destroy'])->middleware('can:crm.opportunities.delete');
    Route::put('opportunities/{id}/stage', [OpportunityApiController::class, 'updateStage'])->middleware('can:crm.opportunities.edit');

    // Customers Endpoints
    Route::get('customers', [CustomerApiController::class, 'index'])->middleware('can:crm.customers.view');
    Route::post('customers', [CustomerApiController::class, 'store'])->middleware('can:crm.customers.create');
    Route::get('customers/{id}', [CustomerApiController::class, 'show'])->middleware('can:crm.customers.view');
    Route::put('customers/{id}', [CustomerApiController::class, 'update'])->middleware('can:crm.customers.edit');
    Route::delete('customers/{id}', [CustomerApiController::class, 'destroy'])->middleware('can:crm.customers.delete');

});
