<?php

namespace xGrz\Dhl24\Actions;

use Carbon\Carbon;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Services\DHLTrackingStatusService;

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
            $this->payload['shipmentId'] = $shipment->id;
        } else {
            $this->payload['shipmentId'] = $shipment;
        }

        $tracking = $this->call()?->getTrackAndTraceInfoResult;
        if (!$tracking->events->item) return [];

        $this->data['receivedBy'] = $tracking->receivedBy;
        collect($tracking->events->item)
            ->each(function ($event) {
                $this->data['events'][] = [
                    'status' => DHLTrackingStatusService::findForTracking($event->status, $event->description),
                    'terminal' => $event->terminal,
                    'event_timestamp' => Carbon::parse($event->timestamp),
                ];

            });
        return $this->data['events'];
    }

    public function getReceivedBy(): string|null
    {
        return $this->data['receivedBy'];
    }
}
