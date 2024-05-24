<?php

namespace xGrz\Dhl24\APIStructs\ServicePoint;

use xGrz\Dhl24\Interfaces\WithCombinedAddress;
use xGrz\Dhl24\Traits\Addressable;

class Address implements WithCombinedAddress
{
    use Addressable;

    public ?string $country;
    public ?string $name;
    public ?string $postCode;
    public ?string $city;
    public ?string $street;
    public ?string $houseNumber;
    public ?string $apartmentNumber;

    public function __construct(object $address, string $name = '')
    {
        $this->country = $address->country;
        $this->name = empty($address->name) ? $name : $address->name;
        $this->postCode = $address->postcode;
        $this->city = $address->city;
        $this->street = $address->street;
        $this->houseNumber = $address->houseNumber;
        $this->apartmentNumber = $address->apartmentNumber;
    }

    public function getFullCity(): string
    {
        return self::fullCityBuilder(
            $this->city,
            $this->postCode
        );
    }

    public function getFullStreet(): string
    {
        return self::fullStreetBuilder(
            $this->street,
            $this->houseNumber,
            $this->apartmentNumber
        );
    }

    public function getFullAddress(): string
    {
        return join(', ', [
            self::getFullCity(),
            self::getFullStreet()
        ]);
    }

}
