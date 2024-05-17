<?php

namespace xGrz\Dhl24\Observers;

use xGrz\Dhl24\Api\Structs\Label;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
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

    public function deleted(DHLShipment $shipment): void
    {
        try {
            (new Label($shipment))->delete();
        } catch (DHL24Exception $e) {

        }
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
