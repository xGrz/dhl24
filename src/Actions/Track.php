<?php

namespace xGrz\Dhl24\Actions;

use xGrz\Dhl24\APIStructs\TrackingEvent;
use xGrz\Dhl24\Models\DHLShipment;

class Track extends ApiCalls
{
    protected string $method = 'GetTrackAndTraceInfo';
    protected array $payload = [
        'shipmentId' => null,
    ];

    protected array $data = [
        'receivedBy' => null,
        'events' => [],
    ];

    public function get(DHLShipment|string|int $shipment): array
    {
        if ($shipment instanceof DHLShipment) {
            $this->payload['shipmentId'] = $shipment->number;
        } else {
            $this->payload['shipmentId'] = $shipment;
        }

        $tracking = $this->call()?->getTrackAndTraceInfoResult;
        if (!$tracking->events->item) return [];

        $this->data['receivedBy'] = $tracking->receivedBy;
        self::processEvents($tracking->events->item);
        return $this->data['events'];
    }

    private function processEvents($events): void
    {
        // When single event is found API returns that object. When multiple events are present API returns array of event objects.
        is_array($events)
            ? collect($events)->each(fn($ev) => self::setEvent($ev))
            : $this->setEvent($events);
    }

    private function setEvent($event): void
    {
        $this->data['events'][] = new TrackingEvent(
            $event->status,
            $event->terminal,
            $event->timestamp,
            $event->description
        );
    }

    public function getReceivedBy(): string|null
    {
        return $this->data['receivedBy'];
    }
}
