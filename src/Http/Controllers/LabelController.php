<?php

namespace xGrz\Dhl24\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\Dhl24\Api\Structs\Label;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLShipment;

class LabelController extends Controller
{
    public function __invoke(DHLShipment $shipment)
    {
        try {
            return (new Label($shipment->number))->download();
        } catch (DHL24Exception $e) {
            return back()->with('danger', $e->getMessage());
        }
    }
}
