<?php

namespace xGrz\Dhl24\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PostalCodeServices extends ApiCalls
{
    protected string $method = 'getPostalCodeServices';
    protected array $payload = [
        'postCode' => null,
        'pickupDate' => null,
    ];

    private array $result = [];

    public function get(string $postalCode, Carbon $pickupDate): static
    {
        $this->payload['postCode'] = $postalCode;
        $this->payload['pickupDate'] = $pickupDate->format('Y-m-d');
        $this->result = Cache::remember(
            'DHLPostalCodeServices:'.join(':', $this->payload),
            30,
            fn() => json_decode(json_encode($this->call()->getPostalCodeServicesResult), true),
        );
        return $this;
    }

    public function exPickupStart(): string
    {
        return $this->result['exPickupFrom'] === 'brak'
            ? '0:00'
            : $this->result['exPickupFrom'];
    }

    public function exPickupEnd(): string
    {
        return $this->result['exPickupTo'] === 'brak'
            ? '0:00'
            : $this->result['exPickupTo'];
    }

    public function drPickupStart(): string
    {
        return $this->result['drPickupFrom'] === 'brak'
            ? '0:00'
            : $this->result['drPickupFrom'];

    }

    public function drPickupEnd(): string
    {
        return $this->result['drPickupTo'] === 'brak'
            ? '0:00'
            : $this->result['drPickupTo'];
    }

    public function getPickupDate(): string
    {
        return $this->payload['pickupDate'];
    }
}
