<?php

use xGrz\Dhl24\Api\Actions\GetPrice;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Wizard\ShipmentWizard;

function array_to_xml($data, $xml = null) {
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
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
            $wizard = new ShipmentWizard();
            $wizard->shipper()
                ->setName('ACME Corp LTD.')
                ->setPostalCode('03200')
                ->setCity('Warszawa')
                ->setStreet('Bonaparte')
                ->setHouseNumber('200', 20)
                ->setContactPerson('John Doe')
                ->setContactPhone('500600800')
                ->setContactEmail('john@doe.com');
            $wizard->receiver()
                ->setName('Microsoft Corp LTD.')
                ->setPostalCode('02777')
                ->setCity('Kraków')
                ->setStreet('Zakopiańska')
                ->setHouseNumber('2')
                ->setContactPerson('Johnny Balboa')
                ->setContactPhone('400400400')
                ->setContactEmail('johnnybalboa@doe.com');
            $wizard->services()->setInsurance(400);
            $wizard->addItem(ShipmentItemType::ENVELOPE);
            $wizard->addItem(ShipmentItemType::PACKAGE, 1, 40, 35, 30, 12);

            dd($wizard->toArray());

//            return response(array_to_xml($wizard->toArray()), 200, [
//                "Content-Type" => 'Content-Type: text/xml;'
//            ]);
            dump(
                (new GetPrice($wizard))->call()->getPrice(),
                DHL24::getPrice($wizard),
                DHL24::getPriceOptions($wizard),
                DHL24::getOptions($wizard),

        );
        });
    });

