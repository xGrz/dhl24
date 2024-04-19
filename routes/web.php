<?php

use xGrz\Dhl24\Api\Structs\Shipment\PaymentData;
use xGrz\Dhl24\Api\Structs\Shipment\ServiceDefinition;
use xGrz\Dhl24\Enums\ShipmentType;


Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $paymentData = new PaymentData();
            $serviceDefinition = new ServiceDefinition(ShipmentType::DOMESTIC12);
            dump(
//                $paymentData,
            $serviceDefinition->setInsurance(320),
                $serviceDefinition->setCollectOnDelivery(320, 'FA/0201/2014'),
                $serviceDefinition->setInsurance(399),
                $serviceDefinition->setCollectOnDelivery(399, 'FA/0202/2014'),
                $serviceDefinition->setInsurance(302),
                $serviceDefinition->setCollectOnDelivery(502, 'FA/0203/2014'),
            );
        });
    });

