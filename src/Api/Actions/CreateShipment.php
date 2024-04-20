<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Api\Structs\Shipment;

class CreateShipment extends BaseApiAction
{
    protected ?string $serviceName = 'createShipments';

    public AuthData $authData;
    public array $shipments = [];

    public function __construct(Shipment $shipment)
    {
        $this->shipments[] = $shipment->toArray();
    }

    public function debug()
    {
        return $this->getPayload();
    }
}
