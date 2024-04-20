<?php

namespace xGrz\Dhl24\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class TestController extends Controller
{
    public function index()
    {
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
    }
}
