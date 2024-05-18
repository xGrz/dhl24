<?php

namespace xGrz\Dhl24\Api\Actions;


use xGrz\Dhl24\Api\Structs\AuthData;

class GetTracking extends BaseApiAction
{
    protected ?string $serviceName = 'getTrackAndTraceInfo';

    public AuthData $authData;

    public string $shipmentId;

    private function __construct(string|int $shipmentNumber)
    {
        $this->shipmentId = $shipmentNumber;
    }

    public static function make(string|int $shipmentNumber): self
    {
        return new self($shipmentNumber);
    }
}
