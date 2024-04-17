<?php

use xGrz\Dhl24\Api\Actions\DailyShippingConfirmationList;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $request = new DailyShippingConfirmationList();
            return $request->download();
//            dd(
//                Version::getVersion(),
//                $request->download()
//            );
        });
    });
