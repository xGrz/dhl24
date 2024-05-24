<?php

namespace xGrz\Dhl24\Actions;

use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;

class CreateShipment extends ApiCalls
{
    protected string $method = 'createShipments';
    protected array $payload = [
        'shipments' => []
    ];

    public function create(DHLShipmentWizard|DHLShipment $shipment): ?string
    {
        if ($shipment instanceof DHLShipment) {
            $shipment = new DHLShipmentWizard($shipment);
        }
        $this->payload['shipments'][] = $shipment->getPayload();

        return $this->call()->createShipmentsResult?->item->shipmentId;
    }
}
