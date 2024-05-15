<?php

namespace xGrz\Dhl24\Listeners;


use xGrz\Dhl24\Events\ShipmentCreatedEvent;

class GetShipmentLabelListener
{
    public function __construct()
    {
    }

    public function handle(ShipmentCreatedEvent $event): void
    {

    }
}
