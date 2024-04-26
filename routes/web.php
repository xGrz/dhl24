<?php

use xGrz\Dhl24\Http\Controllers\CourierBookingsController;
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
    });

