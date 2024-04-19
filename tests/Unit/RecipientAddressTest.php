<?php

use Tests\TestCase;
use xGrz\Dhl24\Api\Structs\Shipment\ReceiverAddress;

class RecipientAddressTest extends TestCase
{

    private ReceiverAddress $receiverAddress;

    protected function setUp(): void
    {
        parent::setUp();
        $this->receiverAddress = new ReceiverAddress();
    }

    public function test_set_name(): void
    {
        $this->receiverAddress->setName('"ACME \' Company LTD"');
        $this->assertEquals('ACME Company LTD', $this->receiverAddress->name);
    }

    public function test_set_long_name_is_cut_after_60_characters(): void
    {
        $this->receiverAddress->setName('ACME Company LTD, ACME Company LTD, ACME Company LTD, ACME Company LTD, ACME Company LTD');

        $this->assertEquals('ACME Company LTD, ACME Company LTD, ACME Company LTD, ACME C', $this->receiverAddress->name);
        $this->assertEquals(60, strlen($this->receiverAddress->name));
    }

    public function test_set_postal_code(): void
    {
        $this->receiverAddress->setPostalCode("01-200");
        $this->assertEquals('01-200', $this->receiverAddress->postalCode);
    }

    public function test_set_city(): void
    {
        $this->receiverAddress->setCity('Warsaw');

        $this->assertEquals('Warsaw', $this->receiverAddress->city);
    }

    public function test_set_street(): void
    {
        $this->receiverAddress->setStreet('1st Avenue');

        $this->assertEquals('1st Avenue', $this->receiverAddress->street);
    }

    public function test_set_house_number_without_apartment(): void
    {
        $this->receiverAddress->setHouseNumber('1');
        $this->assertEquals('1', $this->receiverAddress->houseNumber);
    }

    public function test_set_house_number_with_apartment_together()
    {
        $this->receiverAddress->setHouseNumber('10/1');
        $this->assertEquals('10/1', $this->receiverAddress->houseNumber);
    }

    public function test_set_house_number_with_apartment_separately()
    {
        $this->receiverAddress->setHouseNumber('10', '1');
        $this->assertEquals('10/1', $this->receiverAddress->houseNumber);
    }

    public function testSetContactPerson()
    {
        $this->receiverAddress->setContactPerson('John Travolta');
        $this->assertEquals('John Travolta', $this->receiverAddress->contactPerson);
    }

    public function testSetContactPhone()
    {
        $this->receiverAddress->setContactPhone('0123456789');
        $this->assertEquals('0123456789', $this->receiverAddress->contactPhone);
    }

    public function testSetContactEmail()
    {
        $this->receiverAddress->setContactEmail('john@doe.com');
        $this->assertEquals('john@doe.com', $this->receiverAddress->contactEmail);

    }

    public function test_set_parcel_station_delivery()
    {
        $this->receiverAddress
            ->setStreet('Some street name')
            ->setCity('Poznan')
            ->setPostalCode('20001')
            ->setHouseNumber('23')
            ->setParcelStationDelivery('ABCDS');

        $this->assertNull($this->receiverAddress->city);
        $this->assertNull($this->receiverAddress->street);
        $this->assertNull($this->receiverAddress->postalCode);
        $this->assertEquals('ABCDS', $this->receiverAddress->houseNumber);
        $this->assertFalse($this->receiverAddress->isPostfiliale);
        $this->assertTrue($this->receiverAddress->isPackstation);
    }


    public function test_set_parcel_shop_delivery()
    {
        $this->receiverAddress
            ->setStreet('Some street name')
            ->setCity('Poznan')
            ->setPostalCode('20001')
            ->setHouseNumber('23')
            ->setParcelShopDelivery('ABCDS');

        $this->assertNull($this->receiverAddress->city);
        $this->assertNull($this->receiverAddress->street);
        $this->assertNull($this->receiverAddress->postalCode);
        $this->assertEquals('ABCDS', $this->receiverAddress->houseNumber);
        $this->assertFalse($this->receiverAddress->isPackstation);
        $this->assertTrue($this->receiverAddress->isPostfiliale);
    }

}
