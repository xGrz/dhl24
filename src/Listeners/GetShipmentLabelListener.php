<?php

namespace xGrz\Dhl24\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Facades\DHLConfig;

class GetShipmentLabelListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(ShipmentCreatedEvent $event): void
    {
        if (DHLConfig::shouldStoreLabels()) {
            DHL24::label($event->shipment);
        }
    }
}
