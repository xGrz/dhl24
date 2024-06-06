<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use xGrz\Dhl24\Actions\Track;
use xGrz\Dhl24\APIStructs\TrackingEvent;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Events\ShipmentDeliveredEvent;
use xGrz\Dhl24\Events\ShipmentSentEvent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Jobs\TrackShipmentJob;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Models\DHLTrackingState;

class DHLTrackingService
{
    public ?DHLShipment $shipment;
    protected array $eventDispatcher = [
        DHLStatusType::SENT->name => ShipmentSentEvent::class,
        DHLStatusType::DELIVERED->name => ShipmentDeliveredEvent::class,
        DHLStatusType::PICKED_UP->name => ShipmentDeliveredEvent::class,
    ];

    /**
     * @throws DHL24Exception
     */
    public function __construct(DHLShipment $shipment)
    {
        $this->shipment = $shipment->loadMissing('tracking');
    }

    /**
     * @param TrackingEvent[] $trackingEvents
     */
    public function processEvents(array $trackingEvents): static
    {
        $newEventsCount = 0;
        foreach ($trackingEvents as $event) {
            self::addEvent($event);
        }
        return $this;
    }

    public function addEvent(TrackingEvent $event): static
    {
        if (self::eventExists($event)) return $this;

        $this
            ->shipment
            ->tracking()
            ->attach(
                $event->code,
                ['terminal' => $event->terminal, 'event_timestamp' => $event->timestamp]
            );
        self::trackingEventDispatcher($event);
        $this->shipment->load('tracking');
        return $this;
    }

    public function eventExists(TrackingEvent $trackingEvent): bool
    {
        return $this->shipment
            ->tracking
            ->filter(fn($event) => $event->code === $trackingEvent->code
                && $event->pivot->terminal === $trackingEvent->terminal
                && $event->pivot->event_timestamp->equalTo($trackingEvent->timestamp)
            )->count();
    }

    private function trackingEventDispatcher(TrackingEvent $trackingEvent): void
    {
        foreach ($this->eventDispatcher as $typeName => $event) {
            if (DHLStatusType::findByName($typeName) === $trackingEvent->state->type) {
                $event::dispatch($this->shipment);
            }
        }
    }

    public static function getUndeliveredShipments(): Collection
    {
        return DHLShipment::whereDoesntHave('tracking', function ($q) {
            $q->whereIn('code', self::finishingStates());
        })
            ->where('updated_at', '>', now()->subDays(DHLConfig::getTrackingMaxShipmentAge())->startOfDay())
            ->where('shipment_date', '>', now()->subDays(DHLConfig::getTrackingMaxShipmentAge())->startOfDay())
            ->get();

    }


    public static function finishingStates(): array
    {
        return DHLTrackingState::finishedState()->get()->map(function ($status) {
            return $status->code;
        })->toArray();
    }


    /**
     * @throws DHL24Exception
     */
    public function getTracking(): array
    {
        if (!$this->shipment->number) throw new DHL24Exception('Shipment number not assigned');
        Log::info('Shipment tracking executed', [
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->number
        ]);
        return (new Track())->get($this->shipment->number);
    }

    /**
     * @throws DHL24Exception
     */
    public function updateTracking(): void
    {
        $trackingEvents = self::getTracking();
        if (empty($trackingEvents)) return;
        self::processEvents($trackingEvents);
    }


    /**
     * @throws DHL24Exception
     */
    public static function updateAll(bool $shouldBeDispatchedAsJob = true): void
    {
        foreach (self::getUndeliveredShipments() as $shipment) {
            $shouldBeDispatchedAsJob
                ? TrackShipmentJob::dispatch($shipment)
                : (new static($shipment))->updateTracking();
        }

    }


}
