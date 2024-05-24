<?php

namespace xGrz\Dhl24\Observers;

use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentObserver
{
    public function created(DHLShipment $shipment): void
    {
        self::updateItems($shipment);
        if ($shipment->number) {
            event(new ShipmentCreatedEvent($shipment));
        }

    }

    public function updating(DHLShipment $shipment): void
    {
        self::updateItems($shipment);
        if ($shipment->number && $shipment->isDirty('number')) {
            event(new ShipmentCreatedEvent($shipment));
        }
    }

    public function deleted(DHLShipment $shipment): void
    {
//        try {
//            (new Label($shipment))->delete();
//        } catch (DHL24Exception $e) {
//
//        }
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
