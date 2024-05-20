<?php

namespace xGrz\Dhl24\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\Dhl24\Api\Actions\GetTracking;
use xGrz\Dhl24\Models\DHLShipment;

class TrackShipmentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public DHLShipment $shipment)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        GetTracking::make($this->shipment->number)->call();
    }

    public function uniqueId(): string
    {
        return $this->shipment->number;
    }
}
