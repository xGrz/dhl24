<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Support\Carbon;
use xGrz\Dhl24\Models\DHLStatus;

class GetTrackingResponse
{
    private string $shipment;
    private array $events = [];

    public function __construct(object $result)
    {
        $this->shipment = $result->getTrackAndTraceInfoResult->shipmentId;
        self::processEvents($result->getTrackAndTraceInfoResult->events->item);
    }


    private function processEvents(object|array $events): void
    {
        if (is_object($events)) $events = [$events];
        foreach ($events as $event) {
            $this->events[] = [
                'status' => self::findStatus($event->status, $event->description),
                'terminal' => $event->terminal,
                'timestamp' => Carbon::parse($event->timestamp)
            ];
        }
    }

    private function findStatus(string $statusSymbol, ?string $description = null): DHLStatus
    {
        return DHLStatus::updateOrCreate(
            ['symbol' => $statusSymbol],
            ['description' => $description],
        );
    }


    public function getShipmentId(): string
    {
        return $this->shipment;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
