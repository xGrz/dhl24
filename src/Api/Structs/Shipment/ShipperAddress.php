<?php

namespace xGrz\Dhl24\Api\Structs\Shipment;

use xGrz\Dhl24\Traits\Addressable;

class ShipperAddress
{

    use Addressable;

    public string $name;
    public string $postalCode;
    public string $city;
    public string $street;
    public string $houseNumber;
    public ?string $contactPerson = null;
    public ?string $contactPhone = null;
    public ?string $contactEmail = null;

    public function setName(string $name): ShipperAddress
    {
        $this->name = str($name)
            ->replace('"', '')
            ->replace("'", '')
            ->replace('  ', ' ')
            ->limit(60, '');
        return $this;
    }

    public function setPostalCode(string $postalCode): ShipperAddress
    {
        $this->postalCode = self::postalCodeToNumber($postalCode);
        return $this;
    }

    public function setCity(string $city): ShipperAddress
    {
        $this->city = str($city)->limit(17, '');
        return $this;
    }

    public function setStreet(string $street): ShipperAddress
    {
        $this->street = str($street)->limit(35, '');
        return $this;
    }

    public function setHouseNumber(string $houseNumber, string $apartmentNumber = null): ShipperAddress
    {
        $this->houseNumber = self::houseNumberWithApartmentFormatter($houseNumber, $apartmentNumber);
        return $this;
    }

    public function setContactPerson(?string $contactPerson): ShipperAddress
    {
        $this->contactPerson = str($contactPerson)->limit(60, '');
        return $this;
    }

    public function setContactPhone(?string $contactPhone): ShipperAddress
    {
        $this->contactPhone = str($contactPhone)->limit(20, '');
        return $this;
    }

    public function setContactEmail(?string $contactEmail): ShipperAddress
    {
        if (strlen($contactEmail) < 60) $this->contactEmail = $contactEmail;
        return $this;
    }


}
