<?php

use xGrz\Dhl24\Http\Controllers\CourierBookingsController;
use xGrz\Dhl24\Http\Controllers\SettingsContentsController;
use xGrz\Dhl24\Http\Controllers\SettingsController;
use xGrz\Dhl24\Http\Controllers\SettingsCostCentersController;
use xGrz\Dhl24\Http\Controllers\ShipmentsController;


Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24.')
    ->group(function () {
        Route::get('/', function() {
            return to_route('dhl24.shipments.index');
        });
        Route::resource('/shipments', ShipmentsController::class);
        Route::resource('/bookings', CourierBookingsController::class);
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::resource('/settings/costCenters', SettingsCostCentersController::class);
        Route::resource('/settings/contents', SettingsContentsController::class);
    });

