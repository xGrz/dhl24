<?php

namespace xGrz\Dhl24\Wizard\Components\Address;

use xGrz\Dhl24\Enums\AddressType;

class ReceiverAddress extends Address
{
    public string $addressType = 'C';
    public string $country = 'PL';
    public bool $isPackstation = false;
    public bool $isPostfiliale = false;
    public ?string $postnummer = null;


    public function setAddressType(AddressType $type): ReceiverAddress
    {
        $this->addressType = $type->value;
        return $this;
    }

    public function setCountryCode(string $code): ReceiverAddress
    {
        $this->country = $code;
        return $this;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $this->country === 'PL'
            ? $this->postalCodeToNumber($postalCode)
            : $postalCode;
        return $this;
    }

    public function setCity(string $city): static
    {
        $this->isPackstation = false;
        $this->isPostfiliale = false;
        $this->city = $city;
        return $this;
    }

    public function setStreet(string $street): static
    {
        $this->isPackstation = false;
        $this->isPostfiliale = false;
        $this->street = $street;
        return $this;
    }

    public function setParcelStationDelivery(string $parcelStationId): ReceiverAddress
    {
        self::resetAddress();
        $this->houseNumber = $parcelStationId;
        $this->isPackstation = true;
        return $this;
    }

    public function setParcelShopDelivery(string $parcelShopId): ReceiverAddress
    {
        self::resetAddress();
        $this->houseNumber = $parcelShopId;
        $this->isPostfiliale = true;
        return $this;
    }

    public function setPostNumber(?string $postNumber): ReceiverAddress
    {
        $this->postnummer = $postNumber;
        return $this;
    }

    private function resetAddress(): void
    {
        $this->isPostfiliale = false;
        $this->isPackstation = false;
        $this->postalCode = null;
        $this->street = null;
        $this->city = null;
        $this->houseNumber = '';
        $this->postnummer = null;
    }


}
