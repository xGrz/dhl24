<?php

use xGrz\Dhl24\Http\Controllers\CourierBookingsController;
use xGrz\Dhl24\Http\Controllers\DownloadShipmentController;
use xGrz\Dhl24\Http\Controllers\SettingsContentsController;
use xGrz\Dhl24\Http\Controllers\SettingsController;
use xGrz\Dhl24\Http\Controllers\SettingsCostCentersController;
use xGrz\Dhl24\Http\Controllers\ShipmentsController;


Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24.')
    ->group(function () {
        Route::get('/', function () {
            return to_route('dhl24.shipments.index');
        });
        Route::get('/shipments/{shipment}/label', DownloadShipmentController::class)->name('shipments.label');
        Route::resource('/shipments', ShipmentsController::class);
        Route::resource('/bookings', CourierBookingsController::class);
        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', SettingsController::class)->name('index');
                Route::get('/costCenters', SettingsCostCentersController::class)->name('costCenters.index');
                Route::get('/contents', SettingsContentsController::class)->name('contents.index');
            });
    });

