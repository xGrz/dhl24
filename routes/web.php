<?php

use xGrz\Dhl24\Api\Actions\GetNearestServicePoints;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $point = GetNearestServicePoints::make('03-986', 2)->call()->getPoints(10);
            $point2 = GetNearestServicePoints::make('03-986', 1)->call()->getPoints()->toArray()[1];
            dd(
                $point, $point2
            );
        });
    });

