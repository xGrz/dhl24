<?php

use xGrz\Dhl24\Http\Controllers\CourierBookingsController;
use xGrz\Dhl24\Http\Controllers\ShipmentsController;

function array_to_xml($data, $xml = null)
{
    if ($xml === null) {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
    }

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            array_to_xml($value, $xml->addChild(is_numeric($key) ? 'item' : $key));
        } else {
            $xml->addChild($key, $value);
        }
    }

    return $xml->asXML();
}

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

