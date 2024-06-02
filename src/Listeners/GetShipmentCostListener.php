<?php

namespace xGrz\Dhl24\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Facades\DHL24;

class GetShipmentCostListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(ShipmentCreatedEvent $event): void
    {
        DHL24::wizard($event->shipment)->getCost();
    }
}
