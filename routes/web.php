<?php

use xGrz\Dhl24\Api\Actions\DHL_GetShippingConfirmationList;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            dd(
//                DHL_GetVersion::make()->call(),
//                DHL_GetMyShipments::make(now()->subDays(90), now())->call(),
                  DHL_GetShippingConfirmationList::make(now()->subDays())->call()->isFileStored(),
            );
        });
    });
