<?php

namespace xGrz\Dhl24\Api\Responses;

class CreateShipmentResponse
{
    public string $shipmentId = '';

    public function __construct(object $result)
    {
        $this->shipmentId = $result->createShipmentsResult->item->shipmentId;
    }

    public function getShipmentId(): string
    {
        return $this->shipmentId;
    }
}
