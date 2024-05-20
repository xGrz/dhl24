<?php

namespace xGrz\Dhl24\Actions;

use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Jobs\TrackShipmentJob;

class DHLShipmentsTrackingAction
{
    public static function dispatch(): int
    {
        $undelivered = DHL24::getUndeliveredShipments();

        foreach ($undelivered as $shipment) {
            TrackShipmentJob::dispatch($shipment);
        }

        return $undelivered->count();
    }
}
