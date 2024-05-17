<?php

namespace xGrz\Dhl24\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\Dhl24\Api\Structs\Label;
use xGrz\Dhl24\Models\DHLShipment;

class DownloadShipmentController extends Controller
{
    public function __invoke(DHLShipment $shipment)
    {
        return (new Label($shipment->number))->download();
    }
}
