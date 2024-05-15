<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class GetPrice extends BaseApiAction
{

    public AuthData $authData;
    public array $shipment;

    public function __construct(ShipmentWizard|DHLShipment $shipment)
    {
        $this->shipment = $shipment->getPayload();
    }

    public function debug()
    {
        return $this->getPayload();
    }
}
