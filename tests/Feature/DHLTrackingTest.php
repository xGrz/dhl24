<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\APIStructs\TrackingEvent;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Models\DHLTracking;
use xGrz\Dhl24\Models\DHLTrackingState;
use xGrz\Dhl24\Services\DHLTrackingService;

class DHLTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    private function createRandomShipment(): ?DHLShipment
    {
        return DHLShipment::forceCreateQuietly([
            'number' => rand(10000000000, 90000000000),
            'shipment_date' => now()->subDays(rand(1, DHLConfig::getTrackingMaxShipmentAge()-1))
        ]);
    }

    private function createRandomShipments(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            self::createRandomShipment();
        }
    }

    public function test_get_undelivered_shipments()
    {
        self::createRandomShipments(10);
        $this->assertEquals(10, DHLTrackingService::getUndeliveredShipments()->count());
    }

    public function test_get_undelivered_shipment_with_limited_max_age()
    {
        self::createRandomShipments(10);
        DHLShipment::forceCreateQuietly([
            'number' => rand(10000000000, 90000000000),
            'shipment_date' => now()->subDays(DHLConfig::getTrackingMaxShipmentAge()+2)
        ]);
        $this->assertDatabaseCount(DHLShipment::class, 11);
        $this->assertEquals(10, DHLTrackingService::getUndeliveredShipments()->count());
    }

    public function test_create_tracking_event()
    {
        $event = new TrackingEvent(
            'DOR',
            'Warszawa',
            '2024-06-10 12:12:12',
            'Doręczono przesyłkę do odbiorcy'
        );
        $state = DHLTrackingState::where('code', 'DOR')->first();

        $this->assertEquals('DOR', $event->code);
        $this->assertEquals('Warszawa', $event->terminal);
        $this->assertEquals('2024-06-10 12:12:12', $event->timestamp->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(DHLTrackingState::class, $state);
        $this->assertEquals($state->code, $event->state->code);
    }

    public function test_new_state_is_added_to_database()
    {
        new TrackingEvent(
            'TEST',
            'Warszawa',
            now(),
            'TESTDescription'
        );

        $this->assertDatabaseHas(DHLTrackingState::class, [
            'code' => 'TEST',
            'system_description' => 'TESTDescription',
            'description' => null
        ]);
    }

    public function test_add_single_event_to_shipment_tracking()
    {
        Event::fake();
        $shipment = self::createRandomShipment();
        $event = new TrackingEvent(
            'TEST',
            'Warszawa',
            now(),
            'TESTDescription'
        );
        (new DHLTrackingService($shipment))->addEvent($event);
        $shipment->refresh()->loadMissing('tracking');


        $this->assertCount(1, $shipment->tracking);
        $this->assertDatabaseHas(DHLTracking::class, [
            'shipment_id' => $shipment->id,
            'code_id' => $event->code,
            'terminal' => $event->terminal,
            'event_timestamp' => $event->timestamp
        ]);
    }

    public function test_can_discover_existing_event()
    {
        Event::fake();
        $testTimeStamp = Carbon::parse('2024-01-01 12:00:00');
        $shipment = self::createRandomShipment();
        $event = new TrackingEvent('TEST', 'Warszawa', $testTimeStamp, 'TESTDescription');
        $eventNotFound = new TrackingEvent('TEST-NF', 'Warszawa', now(), 'TESTDescription404');
        (new DHLTrackingService($shipment))->addEvent($event);
        $shipment->refresh()->loadMissing('tracking');

        $this->assertTrue((new DHLTrackingService($shipment))->eventExists($event));
        $this->assertFalse((new DHLTrackingService($shipment))->eventExists($eventNotFound));

    }

    public function test_add_array_of_shipment_tracking_events()
    {
        Event::fake();
        $testTimeStamp = now();
        $shipment = self::createRandomShipment();
        $events[] = new TrackingEvent('TEST1', 'Warszawa', now()->subDays(1), 'TESTDescription1');
        $events[] = new TrackingEvent('TEST2', 'Kraków', now()->subDays(2), 'TESTDescription2');
        $events[] = new TrackingEvent('TEST3', 'Łódź', now()->subDays(3), 'TESTDescription3');
        $events[] = new TrackingEvent('TEST4', 'Poznań', $testTimeStamp, 'TESTDescription4');
        $events[] = new TrackingEvent('TEST4', 'Poznań', $testTimeStamp, 'TESTDescription4');
        (new DHLTrackingService($shipment))->processEvents($events);
        $shipment->refresh()->loadMissing('tracking');

        $this->assertCount(4, $shipment->tracking);
    }

    public function test_sent_shipment_event_is_dispatched()
    {

    }

    public function test_delivered_shipment_event_is_dispatched()
    {

    }

}
