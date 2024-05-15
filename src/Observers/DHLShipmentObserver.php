<?php

namespace xGrz\Dhl24\Observers;

use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentObserver
{
    public function created(DHLShipment $shipment): void
    {
        self::updateItems($shipment);
        event(new ShipmentCreatedEvent($shipment));
    }

    public function updating(DHLShipment $shipment): void
    {
        self::updateItems($shipment);
    }

    private function updateItems(DHLShipment $shipment): void
    {
        if ($shipment->items) {
            foreach ($shipment->items as $item) {
                $shipment->items()->save($item);
            }
        }
    }
}
