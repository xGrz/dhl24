<?php

namespace xGrz\Dhl24\Events;

use Illuminate\Foundation\Events\Dispatchable;
use xGrz\Dhl24\Models\DHLShipment;

class ShipmentSentEvent
{
    use Dispatchable;

    public function __construct(public DHLShipment $shipment)
    {
    }
}
