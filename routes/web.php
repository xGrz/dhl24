<?php

use xGrz\Dhl24\Api\Actions\DailyShippingConfirmationList;
use xGrz\Dhl24\Api\Actions\Version;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $request = new DailyShippingConfirmationList();
            dd(
                Version::getVersion(),
                $request->getDocument()
            );
        });
    });
