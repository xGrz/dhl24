<?php

use Tests\TestCase;
use xGrz\Dhl24\Api\Structs\Shipment\ShipperAddress;

class ShipperAddressTest extends TestCase
{

    private ShipperAddress $shipperAddress;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shipperAddress = new ShipperAddress();
    }

    public function test_set_name(): void
    {
        $this->shipperAddress->setName('"ACME \' Company LTD"');
        $this->assertEquals('ACME Company LTD', $this->shipperAddress->name);
    }

    public function test_set_long_name_is_cut_after_60_characters(): void
    {
        $this->shipperAddress->setName('ACME Company LTD, ACME Company LTD, ACME Company LTD, ACME Company LTD, ACME Company LTD');

        $this->assertEquals('ACME Company LTD, ACME Company LTD, ACME Company LTD, ACME C', $this->shipperAddress->name);
        $this->assertEquals(60, strlen($this->shipperAddress->name));
    }

    public function test_set_postal_code_as_numeric(): void
    {
        $this->shipperAddress->setPostalCode("01-200");
        $this->assertEquals('01200', $this->shipperAddress->postalCode);
    }

    public function test_set_city():void
    {
        $this->shipperAddress->setCity('Warsaw');

        $this->assertEquals('Warsaw', $this->shipperAddress->city);
    }

    public function test_set_street(): void
    {
        $this->shipperAddress->setStreet('1st Avenue');

        $this->assertEquals('1st Avenue', $this->shipperAddress->street);
    }

    public function test_set_house_number_without_apartment(): void
    {
        $this->shipperAddress->setHouseNumber('1');
        $this->assertEquals('1', $this->shipperAddress->houseNumber);
    }

    public function test_set_house_number_with_apartment_together()
    {
        $this->shipperAddress->setHouseNumber('10/1');
        $this->assertEquals('10/1', $this->shipperAddress->houseNumber);
    }

    public function test_set_house_number_with_apartment_separately()
    {
        $this->shipperAddress->setHouseNumber('10', '1');
        $this->assertEquals('10/1', $this->shipperAddress->houseNumber);
    }

    public function testSetContactPerson()
    {
        $this->shipperAddress->setContactPerson('John Travolta');
        $this->assertEquals('John Travolta', $this->shipperAddress->contactPerson);
    }

    public function testSetContactPhone()
    {
        $this->shipperAddress->setContactPhone('0123456789');
        $this->assertEquals('0123456789', $this->shipperAddress->contactPhone);
    }

    public function testSetContactEmail()
    {
        $this->shipperAddress->setContactEmail('john@doe.com');
        $this->assertEquals('john@doe.com', $this->shipperAddress->contactEmail);

    }

}
