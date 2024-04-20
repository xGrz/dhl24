<?php

namespace xGrz\Dhl24\Wizard\Components\Address;

class ShipperAddress extends Address
{
    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $this->postalCodeToNumber($postalCode);
        return $this;
    }

}
