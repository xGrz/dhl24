<?php

namespace xGrz\Dhl24\Http\Controllers;


use xGrz\Dhl24\Models\DHLCourierBooking;

class CourierBookingsController extends BaseController
{
    public function index()
    {
        return view('dhl::bookings.index', [
            'title' => 'Courier Bookings',
            'bookings' => DHLCourierBooking::latest()->paginate()
        ]);
    }
}
