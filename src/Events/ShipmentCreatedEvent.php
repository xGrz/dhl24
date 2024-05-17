<?php

namespace xGrz\Dhl24\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use xGrz\Dhl24\Models\DHLShipment;

class ShipmentCreatedEvent implements ShouldQueue
{
    use Dispatchable;

    public function __construct(public DHLShipment $shipment)
    {
    }
}
