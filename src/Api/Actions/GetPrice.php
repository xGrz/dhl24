<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class GetPrice extends BaseApiAction
{

    public AuthData $authData;
    public array $shipment;

    public function __construct(ShipmentWizard $shipment)
    {
        $this->shipment = $shipment->toArray();
    }

    public function debug()
    {
        return $this->getPayload();
    }
}
