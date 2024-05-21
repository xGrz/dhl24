<?php

namespace xGrz\Dhl24\Actions;

use xGrz\Dhl24\Api\Actions\GetTracking;
use xGrz\Dhl24\Enums\StatusType;
use xGrz\Dhl24\Events\ShipmentDeliveredEvent;
use xGrz\Dhl24\Events\ShipmentSentEvent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Models\DHLStatus;

class DHLShipmentTracking
{
    private array $events = [];

    private function __construct(protected DHLShipment $shipment)
    {
        self::getTracking();
    }

    private function getTracking(): void
    {
        $tracking = GetTracking::make($this->shipment->number)->call();
        $this->events = $tracking->getEvents();
        foreach ($tracking->getEvents() as $event) {
            if (!self::eventExists($event)) {
                $this->shipment->tracking()->attach($event['status'], ['terminal' => $event['terminal'], 'event_timestamp' => $event['timestamp']]);
                self::trackingEventDispatcher($event['status']);
            }
        }
    }

    private function trackingEventDispatcher(DHLStatus $status): void
    {
        if ($status->type === StatusType::SENT) {
            ShipmentSentEvent::dispatch($this->shipment);
        } elseif ($status->type === StatusType::DELIVERED || $status->type === StatusType::PICKED_UP) {
            ShipmentDeliveredEvent::dispatch($this->shipment);
        }
    }

    private function eventExists($event): bool
    {
        return $this->shipment->tracking->filter(function ($trackingEvent) use ($event) {
            return $trackingEvent->symbol === $event['status']->symbol
                && $trackingEvent->pivot->terminal === $event['terminal']
                && $trackingEvent->pivot->event_timestamp->equalTo($event['timestamp']);
        })->count();
    }

    public function getEvents(): array
    {
        $events = [];
        foreach ($this->events as $event) {
            $events[] = [
                'symbol' => $event['status']->symbol,
                'description' => $event['status']->description,
                'custom_description' => $event['status']->custom_description,
                'type_id' => $event['status']->type->value,
                'type_name' => $event['status']->type->name,
                'state' => $event['status']->type->getState(),
                'terminal' => $event['terminal'],
                'timestamp' => $event['timestamp']
            ];
        }

        return $events;
    }

    public static function from(DHLShipment $shipment): static
    {
        $shipment->loadMissing('tracking');
        return new static($shipment);
    }

    public static function fromId(int $shipmentId): static
    {
        $shipment = DHLShipment::with('tracking')
            ->findOr($shipmentId, 'id', fn() => throw new DHL24Exception('DHL shipment id [' . $shipmentId . '] not found.'))
            ->first();
        return new static($shipment);
    }

    public static function fromNumber(string|int $shipmentNumber): static
    {
        $shipment = DHLShipment::with('tracking')
            ->findOr($shipmentNumber, 'number', fn() => throw new DHL24Exception('DHL shipment [' . $shipmentNumber . '] number not found.'))
            ->first();
        return new static($shipment);

    }

}
