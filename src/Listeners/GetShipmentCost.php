<?php

namespace xGrz\Dhl24\Listeners;


use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Facades\DHL24;

class GetShipmentCost
{
    public function __construct()
    {
    }

    public function handle(ShipmentCreatedEvent $event): void
    {
        $event->shipment->update([
            'cost' => DHL24::getPrice($event->shipment)
        ]);
    }
}
