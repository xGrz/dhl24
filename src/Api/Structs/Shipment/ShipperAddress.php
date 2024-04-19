<?php

namespace xGrz\Dhl24\Api\Structs\Shipment;

use xGrz\Dhl24\Api\Structs\Shipment\Address\BaseAddress;

class ShipperAddress extends BaseAddress
{
    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $this->postalCodeToNumber($postalCode);
        return $this;
    }

}
