<?php

namespace xGrz\Dhl24\Observers;

use Illuminate\Support\Facades\Bus;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Jobs\GetShipmentCostJob;
use xGrz\Dhl24\Jobs\GetShipmentLabelJob;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentObserver
{
    /**
     * @throws \Throwable
     */
    public function created(DHLShipment $shipment): void
    {
        self::updateItems($shipment);
        self::dispatchFinalizeShipmentJobs($shipment);
    }

    public function updating(DHLShipment $shipment): void
    {
        self::updateItems($shipment);
        self::dispatchFinalizeShipmentJobs($shipment);
    }

    public function updated(DHLShipment $shipment): void
    {
//        ShipmentCreatedEvent::dispatch($shipment);
    }

    private function updateItems(DHLShipment $shipment): void
    {
        if ($shipment->items) {
            foreach ($shipment->items as $item) {
                $shipment->items()->save($item);
            }
        }
    }

    private function dispatchFinalizeShipmentJobs(DHLShipment $shipment): void
    {
        if (!empty($shipment->number) && ($shipment->isDirty('number'))) {
            Bus::batch([
                [
                    new GetShipmentLabelJob($shipment),
                    new GetShipmentCostJob($shipment),
                ]
            ])
                ->then(
                    fn() => ShipmentCreatedEvent::dispatch($shipment)
                )
                ->dispatch();
        }
    }


}
