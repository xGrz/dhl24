<?php

namespace xGrz\Dhl24\Http\Controllers;

use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;
use xGrz\Dhl24\Models\DHLShipment;

class ShipmentsController extends BaseController
{
    public function index()
    {
        return view('dhl::shipments.index', [
            'title' => 'Shipments',
            'shipments' => DHLShipment::with(['items', 'courier_booking', 'cost_center'])->latest()->paginate()
        ]);
    }

    public function create()
    {
        return view('dhl::shipments.create', [
            'title' => 'Crete shipment',
        ]);
    }

    public function store(StoreShipmentRequest $request)
    {
        dd($request->all());
    }
}
