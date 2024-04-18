<?php

use xGrz\Dhl24\Api\Actions\GetMyShipments;
use xGrz\Dhl24\Api\Actions\GetShippingConfirmationList;
use xGrz\Dhl24\Api\Actions\GetVersion;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            dd(
                GetVersion::make()->call(),
                GetMyShipments::make(now()->subDays(90), now())->call(),
                GetShippingConfirmationList::make(now()->subDays())->call()->isFileStored(),
            );
        });
    });
