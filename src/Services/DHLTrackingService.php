<?php

namespace xGrz\Dhl24\Services;

use Illuminate\Support\Collection;
use xGrz\Dhl24\Actions\Track;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Events\ShipmentDeliveredEvent;
use xGrz\Dhl24\Events\ShipmentSentEvent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Models\DHLStatus;

class DHLTrackingService
{

    protected array $eventDispatcher = [
        DHLStatusType::SENT->name => ShipmentSentEvent::class,
        DHLStatusType::DELIVERED->name => ShipmentDeliveredEvent::class,
        DHLStatusType::PICKED_UP->name => ShipmentDeliveredEvent::class,
    ];

    /**
     * @throws DHL24Exception
     */
    public function __construct(protected DHLShipment $shipment)
    {
        $this->shipment->loadMissing('tracking');
        self::getTracking();
    }

    /**
     * @throws DHL24Exception
     */
    private function getTracking(): void
    {
        if (!$this->shipment->number) throw new DHL24Exception('Shipment number not assigned');
        $trackingEvents = (new Track())->get($this->shipment->number);
        if (empty($trackingEvents)) return;
        foreach ($trackingEvents as $event) {
            if (!self::eventExists($event)) {
                $this
                    ->shipment
                    ->tracking()
                    ->attach($event['status'], ['terminal' => $event['terminal'], 'event_timestamp' => $event['event_timestamp']]);

                self::trackingEventDispatcher($event['status']);
            }
        }
    }

    private function eventExists(array $event): bool
    {
        return $this->shipment->tracking->filter(function ($trackingEvent) use ($event) {
            return $trackingEvent->symbol === $event['status']->symbol
                && $trackingEvent->pivot->terminal === $event['terminal']
                && $trackingEvent->pivot->event_timestamp->equalTo($event['event_timestamp']);
        })->count();
    }

    /**
     * @throws DHL24Exception
     */
    private function trackingEventDispatcher(DHLStatus $status): void
    {
        foreach ($this->eventDispatcher as $typeName => $event) {
            if (DHLStatusType::findByName($typeName) === $status->type) {
                $event::dispatch($this->shipment);
            }
        }
    }

    public static function getUndeliveredShipments(): Collection
    {
        return DHLShipment::whereDoesntHave('tracking', function ($q) {
            $q->whereIn('status', self::finishingStates());
        })->get();
    }

    public static function finishingStates(): array
    {
        return DHLStatus::finishedState()->get()->map(function ($status) {
            return $status->symbol;
        })->toArray();
    }

}
