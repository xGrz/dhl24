<?php

namespace xGrz\Dhl24\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Services\DHLTrackingService;

class TrackShipmentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 170;

    public function __construct(public DHLShipment $shipment)
    {
    }

    /**
     * @throws DHL24Exception
     */
    public function handle(): void
    {
        (new DHLTrackingService($this->shipment))->updateTracking();
    }

    public function uniqueId(): string
    {
        return 'ShipmentId:' . $this->shipment->id;
    }

}
