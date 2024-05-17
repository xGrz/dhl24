<?php

namespace xGrz\Dhl24\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use xGrz\Dhl24\Api\Structs\Label;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\Config;

class GetShipmentLabelListener implements ShouldQueue
{
    public function __construct()
    {
    }

    /**
     * @throws DHL24Exception
     */
    public function handle(ShipmentCreatedEvent $event): void
    {
        if (Config::shouldStoreLabels()) {
            new Label($event->shipment);
        }
    }
}
