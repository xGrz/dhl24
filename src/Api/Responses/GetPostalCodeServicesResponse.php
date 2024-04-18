<?php

namespace xGrz\Dhl24\Api\Responses;

class GetPostalCodeServicesResponse
{
    private bool $domesticExpress9 = false;
    private bool $domesticExpress12 = false;
    private bool $deliveryEvening = false;
    private bool $pickupOnSaturday = false;
    private bool $deliverySaturday = false;
    private ?string $exPickupFrom = '';
    private ?string $exPickupTo = '';
    private ?string $drPickupFrom = '';
    private ?string $drPickupTo = '';

    public function __construct(object $result)
    {
        $response = $result->getPostalCodeServicesResult;
        $this->domesticExpress9 = $response->domesticExpress9;
        $this->domesticExpress12 = $response->domesticExpress12;
        $this->deliveryEvening = $response->deliveryEvening;
        $this->pickupOnSaturday = $response->pickupOnSaturday;
        $this->deliverySaturday = $response->deliverySaturday;
        $this->exPickupFrom = self::timeConverter($response->exPickupFrom);
        $this->exPickupTo = self::timeConverter($response->exPickupTo);
        $this->drPickupFrom = self::timeConverter($response->drPickupFrom);
        $this->drPickupTo = self::timeConverter($response->drPickupTo);
    }

    private function timeConverter(string $time): ?string
    {
        return $time !== 'brak'
            ? $time
            : null;
    }

    public function pickup($toArray = false): object|array
    {
        $pickup = new \stdClass();
        $pickup->onSaturday = $this->pickupOnSaturday;
        $pickup->expressFrom = $this->exPickupFrom;
        $pickup->expressTo = $this->exPickupTo;
        $pickup->generalCargoFrom = $this->drPickupFrom;
        $pickup->generalCargoTo = $this->drPickupTo;
        return $toArray
            ? json_decode(json_encode($pickup), true)
            : $pickup;

    }

    public function delivery($toArray = false): object|array
    {
        $delivery = new \stdClass();
        $delivery->domesticExpress9 = $this->domesticExpress9;
        $delivery->domesticExpress12 = $this->domesticExpress12;
        $delivery->onSaturday = $this->deliverySaturday;
        $delivery->evening = $this->deliveryEvening;
        return $toArray
            ? json_decode(json_encode($delivery), true)
            : $delivery;
    }
}
