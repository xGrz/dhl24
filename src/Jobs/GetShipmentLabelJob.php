<?php

namespace xGrz\Dhl24\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class GetShipmentLabelJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public DHLShipment $shipment)
    {
    }

    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        DHL24::label($this->shipment);
    }
}
