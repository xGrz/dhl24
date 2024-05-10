<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class CreateShipment extends BaseApiAction
{
    protected ?string $serviceName = 'createShipments';

    public AuthData $authData;
    public array $shipments = [];

    public function __construct(ShipmentWizard $shipment)
    {
        $this->shipments[] = $shipment->toArray();
    }

    public function debug(): array
    {
        return $this->getPayload();
    }

    public static function make(ShipmentWizard $wizard)
    {
        return new self($wizard);
    }
}
