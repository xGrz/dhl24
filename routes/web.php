<?php

use xGrz\Dhl24\Api\Actions\GetLabel;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            dd(
                GetLabel::make("27563594371")->call()->store(),
                GetLabel::make("27571475525", 27582550740)->call()->store()
            );
        });
    });

