<?php

namespace xGrz\Dhl24\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Services\DHLTrackingService;

class TrackShipmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): int
    {
        $undelivered = DHLTrackingService::getUndeliveredShipments();
        $undelivered->count()
            ? Log::info('TrackShipmentsJob: ' .$undelivered->count(). ' undelivered shipment(s).')
            : Log::info('TrackShipmentsJob: undelivered shipments not found');

        return $undelivered
            ->each(function (DHLShipment $shipment) {
                TrackShipmentJob::dispatch($shipment);
            })
            ->count();
    }

}
