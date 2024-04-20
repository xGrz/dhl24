<?php

use xGrz\Dhl24\Api\Actions\GetPrice;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Wizard\ShipmentWizard;

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
    ->name('dhl24')
    ->group(function () {
        Route::get('/', function () {
//
//            $d = DHLShipment::with(['courier_booking'])->find(4);
//            $b = DHLCourierBooking::with(['shipments'])->first();
//            dd($d->toArray(), $b->toArray());

            $faker = Faker\Factory::create('pl_PL');

            $wizard = new ShipmentWizard();
            $wizard->shipper()
                ->setName('ACME Corporation LTD.')
                ->setPostalCode('03200')
                ->setCity('Warszawa')
                ->setStreet('Bonaparte')
                ->setHouseNumber('200', 20)
                ->setContactPerson('John Doe')
                ->setContactPhone('500600800')
                ->setContactEmail('john@doe.com');
            $wizard->receiver()
                ->setName($faker->boolean('70') ? $faker->company() : $faker->firstNameMale() . ' ' . $faker->lastName())
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setStreet($faker->streetName)
                ->setHouseNumber($faker->buildingNumber)
                ->setContactPerson($faker->firstNameMale() . ' ' . $faker->lastName())
                ->setContactPhone($faker->phoneNumber())
                ->setContactEmail($faker->safeEmail());
            $wizard->services()->setInsurance(rand(30000, 240000) / 100);
            $wizard->services()->setCollectOnDelivery(rand(30000, 240000) / 100);
            $wizard->addItem(ShipmentItemType::ENVELOPE);
            $wizard->addItem(ShipmentItemType::PACKAGE, rand(1, 2), rand(30, 60), rand(20, 30), rand(10, 40), rand(1, 30));

            dd($wizard->store());

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

