<?php

namespace xGrz\Dhl24\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Services\DHLTrackingService;

class TrackShipmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): int
    {
        return DHLTrackingService::getUndeliveredShipments()
            ->each(function (DHLShipment $shipment) {
                TrackShipmentJob::dispatch($shipment);
            })
            ->count();
    }

}
