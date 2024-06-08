<?php

namespace xGrz\Dhl24\Actions;

use Carbon\Carbon;

class PostalCodeServices extends ApiCalls
{
    protected string $method = 'getPostalCodeServices';
    protected array $payload = [
        'postCode' => null,
        'pickupDate' => null,
    ];

    private array $result = [];

    public function get(string $postalCode, Carbon $pickupDate)
    {
        $this->payload['postCode'] = $postalCode;
        $this->payload['pickupDate'] = $pickupDate->format('Y-m-d');
        $result = $this->call()->getPostalCodeServicesResult;
        $this->result = json_decode(json_encode($result), true);
        dump($this->result);
    }
}
