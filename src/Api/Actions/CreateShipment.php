<?php

namespace xGrz\Dhl24\Api\Actions;

use JetBrains\PhpStorm\NoReturn;
use xGrz\Dhl24\Api\Structs\AuthData;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class CreateShipment extends BaseApiAction
{
    protected ?string $serviceName = 'createShipments';

    public AuthData $authData;
    public array $shipments = [];

    #[NoReturn]
    public function __construct(DHLShipment|ShipmentWizard $shipment)
    {
        $this->shipments[] = $shipment->getPayload();
    }

    public function debug(): array
    {
        return $this->getPayload();
    }

    public static function make(DHLShipment $shipment): CreateShipment
    {
        return new self($shipment);
    }
}
