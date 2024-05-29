<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Facades\DHL24;

class DHLTrackingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_service_dispatches()
    {
        DHL24::updateShipmentTracking();
    }


}
