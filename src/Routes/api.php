<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Crm\Http\Controllers\Api\LeadApiController;
use Dev3bdulrahman\Crm\Http\Controllers\Api\OpportunityApiController;
use Dev3bdulrahman\Crm\Http\Controllers\Api\CustomerApiController;
use Dev3bdulrahman\Crm\Http\Controllers\Api\ActivityApiController;

Route::prefix('api/v1/crm')->middleware(['auth:sanctum', 'throttle:60,1', 'api.tenant'])->group(function () {

    // Leads Endpoints
    Route::get('leads', [LeadApiController::class, 'index'])->middleware('can:crm.leads.view')->name('api.v1.crm.leads.index');
    Route::post('leads', [LeadApiController::class, 'store'])->middleware('can:crm.leads.create')->name('api.v1.crm.leads.store');
    Route::get('leads/{lead}', [LeadApiController::class, 'show'])->middleware('can:crm.leads.view')->name('api.v1.crm.leads.show');
    Route::put('leads/{lead}', [LeadApiController::class, 'update'])->middleware('can:crm.leads.edit')->name('api.v1.crm.leads.update');
    Route::delete('leads/{lead}', [LeadApiController::class, 'destroy'])->middleware('can:crm.leads.delete')->name('api.v1.crm.leads.destroy');
    Route::post('leads/{lead}/convert', [LeadApiController::class, 'convert'])->middleware('can:crm.leads.convert')->name('api.v1.crm.leads.convert');

    // Opportunities Endpoints
    Route::get('opportunities', [OpportunityApiController::class, 'index'])->middleware('can:crm.opportunities.view')->name('api.v1.crm.opportunities.index');
    Route::post('opportunities', [OpportunityApiController::class, 'store'])->middleware('can:crm.opportunities.create')->name('api.v1.crm.opportunities.store');
    Route::get('opportunities/{opportunity}', [OpportunityApiController::class, 'show'])->middleware('can:crm.opportunities.view')->name('api.v1.crm.opportunities.show');
    Route::put('opportunities/{opportunity}', [OpportunityApiController::class, 'update'])->middleware('can:crm.opportunities.edit')->name('api.v1.crm.opportunities.update');
    Route::delete('opportunities/{opportunity}', [OpportunityApiController::class, 'destroy'])->middleware('can:crm.opportunities.delete')->name('api.v1.crm.opportunities.destroy');
    Route::put('opportunities/{opportunity}/stage', [OpportunityApiController::class, 'updateStage'])->middleware('can:crm.opportunities.edit')->name('api.v1.crm.opportunities.update-stage');

    // Customers Endpoints
    Route::get('customers', [CustomerApiController::class, 'index'])->middleware('can:crm.customers.view')->name('api.v1.crm.customers.index');
    Route::post('customers', [CustomerApiController::class, 'store'])->middleware('can:crm.customers.create')->name('api.v1.crm.customers.store');
    Route::get('customers/{customer}', [CustomerApiController::class, 'show'])->middleware('can:crm.customers.view')->name('api.v1.crm.customers.show');
    Route::put('customers/{customer}', [CustomerApiController::class, 'update'])->middleware('can:crm.customers.edit')->name('api.v1.crm.customers.update');
    Route::delete('customers/{customer}', [CustomerApiController::class, 'destroy'])->middleware('can:crm.customers.delete')->name('api.v1.crm.customers.destroy');

    // Activities Endpoints
    Route::get('activities', [ActivityApiController::class, 'index'])->middleware('can:crm.activities.view')->name('api.v1.crm.activities.index');
    Route::post('activities', [ActivityApiController::class, 'store'])->middleware('can:crm.activities.create')->name('api.v1.crm.activities.store');

});
