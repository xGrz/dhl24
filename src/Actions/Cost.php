<?php

namespace xGrz\Dhl24\Actions;

use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;

class Cost extends ApiCalls
{

    protected string $method = 'GetPrice';
    protected array $payload = [
        'shipment' => null
    ];

    private array $data = [
        'basePrice' => 0,
        'fuelSurcharge' => 0,
    ];


    /**
     * @throws DHL24Exception
     */
    public function get(DHLShipment|DHLShipmentWizard $shipment): static
    {
        if ($shipment instanceof DHLShipment) {
            $shipment = new DHLShipmentWizard($shipment);
        }
        $this->payload['shipment'] = $shipment->getPayload();

        $result = $this->call()?->getPriceResult;
        $this->data['basePrice'] = $result->price;
        $this->data['fuelSurcharge'] = $result->fuelSurcharge;
        return $this;
    }

    public function basePrice(): float
    {
        return $this->data['basePrice'];
    }

    public function price(): float
    {
        return $this->basePrice() + $this->fuelSurcharge();
    }

    public function fuelSurcharge(): float
    {
        return round($this->data['basePrice'] * ($this->data['fuelSurcharge'] / 100), 2);
    }

    public function fuelSurchargePercent(): float
    {
        return $this->data['fuelSurcharge'];
    }
}
