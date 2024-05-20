<?php

namespace xGrz\Dhl24\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\Dhl24\Facades\DHL24;

class DispatchTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $undelivered = DHL24::getUndeliveredShipments();
        if ($undelivered->isEmpty()) return;
        foreach ($undelivered as $shipment) {
            TrackShipmentJob::dispatch($shipment);
        }

    }

}
