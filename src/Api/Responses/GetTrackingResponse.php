<?php

namespace xGrz\Dhl24\Api\Responses;

use Carbon\Carbon;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Models\DHLStatus;

class GetTrackingResponse
{
    public DHLShipment $shipment;
    private array $events = [];

    public function __construct(object $result)
    {
        self::getShipment($result->getTrackAndTraceInfoResult->shipmentId);
        self::processEvents($result->getTrackAndTraceInfoResult->events->item);
    }

    private function getShipment(string $shipmentId): void
    {
        $this->shipment = DHLShipment::with(['tracking'])
            ->where('number', $shipmentId)
            ->firstOr(fn() => throw new DHL24Exception('DHL shipment [' . $shipmentId . '] record not found.'));
    }

    private function processEvents($events): void
    {
        foreach ($events as $event) {
            $status = self::findStatus($event->status, $event->description);
            self::setTrackingEvent($event->terminal, Carbon::parse($event->timestamp), $status);
        }
        // sync this

        $this->shipment->tracking()->sync($this->events);
        // dd($this->events);

    }

    private function findStatus(string $statusSymbol, ?string $description = null): DHLStatus
    {
        return DHLStatus::firstOrCreate(
            ['symbol' => $statusSymbol],
            ['description' => $description]
        );
    }

    private function setTrackingEvent(string $terminal, Carbon $eventTimestamp, DHLStatus $status): void
    {
        $hasTracking = $this->shipment
            ->tracking
            ->filter(function ($trackInfo) use ($terminal, $eventTimestamp, $status) {
                return $trackInfo->terminal === $terminal
                    && $trackInfo->status === $status->symbol
                    && $eventTimestamp->equalTo($trackInfo->event_at);
            });
        if ($hasTracking->isNotEmpty()) return;
        $this->events[] = ['status' => $status->symbol, 'terminal' => $terminal, 'event_timestamp' => $eventTimestamp];
    }

}
