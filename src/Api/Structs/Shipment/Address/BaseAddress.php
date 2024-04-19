<?php

namespace xGrz\Dhl24\Api\Structs\Shipment\Address;

use xGrz\Dhl24\Traits\Addressable;

abstract class BaseAddress
{
    use Addressable;

    public ?string $name = null;
    public ?string $postalCode = null;
    public ?string $city = null;
    public ?string $street = null;
    public ?string $houseNumber = null;
    public ?string $contactPerson = null;
    public ?string $contactPhone = null;
    public ?string $contactEmail = null;

    public function setName(string $name): static
    {
        $this->name = str($name)
            ->replace('"', '')
            ->replace("'", '')
            ->replace('  ', ' ')
            ->limit(60, '');
        return $this;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function setCity(string $city): static
    {
        $this->city = str($city)->limit(17, '');
        return $this;
    }

    public function setStreet(string $street): static
    {
        $this->street = str($street)->limit(35, '');
        return $this;
    }

    public function setHouseNumber(string $houseNumber, string $apartmentNumber = null): static
    {
        $this->houseNumber = self::houseNumberWithApartmentFormatter($houseNumber, $apartmentNumber);
        return $this;
    }

    public function setContactPerson(?string $contactPerson): static
    {
        $this->contactPerson = str($contactPerson)->limit(60, '');
        return $this;
    }

    public function setContactPhone(?string $contactPhone): static
    {
        $this->contactPhone = str($contactPhone)->limit(20, '');
        return $this;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        if (strlen($contactEmail) < 60) $this->contactEmail = $contactEmail;
        return $this;
    }


}
