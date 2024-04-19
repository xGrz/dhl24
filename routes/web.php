<?php

use xGrz\Dhl24\Api\Actions\GetNearestServicePoints;
use xGrz\Dhl24\Enums\ServicePointType;

ini_set('memory_limit', '-1');

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $point = GetNearestServicePoints::make('03-986', 2)->call();
            dump(
                $point->getPointsByType(ServicePointType::PARCEL_STATION, 5),
                $point->getPointsByType(ServicePointType::PARCEL_SHOP, 10),
                $point->getPoints()->count(),

            );
        });
    });

