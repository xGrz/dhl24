<?php

namespace xGrz\Dhl24\Api\Responses;

class CreateShipmentResponse
{
    public function __construct(object $result)
    {
        dd($result->createShipmentsResult);
    }
}
