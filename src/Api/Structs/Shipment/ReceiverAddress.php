<?php

namespace xGrz\Dhl24\Api\Structs\Shipment;

class ReceiverAddress extends ShipperAddress
{
    public string $addressType = 'C';
    public string $country = 'PL';
    public ?string $isPackstation = null;
    public ?string $isPostfiliale = null;
    public ?string $postnummer = null;


    public function setAddressType(string $addressType): ReceiverAddress
    {
        $this->addressType = $addressType;
        return $this;
    }

    public function setCountry(string $country): ReceiverAddress
    {
        $this->country = $country;
        return $this;
    }

    public function setPackStation(?string $packstation): ReceiverAddress
    {
        $this->isPackstation = $packstation;
        return $this;
    }

    public function setPostfiliale(?string $postfiliale): ReceiverAddress
    {
        $this->isPostfiliale = $postfiliale;
        return $this;
    }

    public function setPostnummer(?string $postnummer): ReceiverAddress
    {
        $this->postnummer = $postnummer;
        return $this;
    }



}
