<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Events\ShipmentCreatedEvent;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class DHLTrackingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_service_dispatches()
    {
        DHL24::updateShipmentTracking();
    }

    public function test_create_shipment_with_number_dispatches_event()
    {
        Event::fake([ShipmentCreatedEvent::class]);
        DHL24::wizard()->store();
        DHLShipment::first()->update(['number' => 1234567890]);

        Event::assertDispatched(ShipmentCreatedEvent::class);
    }

    public function test_create_shipment_without_number_do_not_dispatches_event()
    {
        Event::fake([ShipmentCreatedEvent::class]);
        DHL24::wizard()->store();

        Event::assertNotDispatched(ShipmentCreatedEvent::class);
    }

}
