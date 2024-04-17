<?php

namespace xGrz\Dhl24\Api\Actions;

use Illuminate\Support\Carbon;
use xGrz\Dhl24\Enums\ShippingConfirmationType;
use xGrz\Dhl24\Services\ConfigService;

class DailyShippingConfirmationList
{

    public function __construct(public ?Carbon $date = null, public ShippingConfirmationType $type = ShippingConfirmationType::ALL)
    {
        if (!$this->date) $this->date = now();
    }

    public function getDocument()
    {
        $payload = [
            'pnpRequest' => [
                'authData' => [
                    'username' => env('DHL24_USER'),
                    'password' => env('DHL24_PASSWORD'),
                ],
                'date' => $this->date->format('Y-m-d'),
                'type' => $this->type->name
            ],
        ];
        $response = (new ConfigService())->connection()->getPnp($payload);
        return $response;
    }
}
