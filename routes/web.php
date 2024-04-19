<?php

use xGrz\Dhl24\Api\Structs\Shipment;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Enums\ShipmentType;


Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $shipment = new Shipment(ShipmentType::DOMESTIC);
            $shipment->shipper
                ->setName('ACME Corp LTD.')
                ->setPostalCode('03-200')
                ->setCity('Warszawa')
                ->setStreet('Bonaparte')
                ->setHouseNumber('200', 20)
                ->setContactPerson('John Doe')
                ->setContactPhone('500600800')
                ->setContactEmail('john@doe.com')
            ;
            $shipment->receiver
                ->setName('ACME Corp LTD.')
                ->setPostalCode('33-200')
                ->setCity('Kraków')
                ->setStreet('Zakopiańska')
                ->setHouseNumber('2')
                ->setContactPerson('Johnny Balboa')
                ->setContactPhone('400400400')
                ->setContactEmail('johnnybalboa@doe.com');

            $shipment->addItem()->setDiamentions(30,30, 30, 3);
            $shipment->addItem(ShipmentItemType::ENVELOPE);
            $shipment->setShipmentContent('Elektronika');
            $shipment->setReference('FZ/0020/2024');

            dump(
                $shipment->toArray()
            );
        });
    });

