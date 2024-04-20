<?php

namespace xGrz\Dhl24\Wizard\Components;

use xGrz\Dhl24\Enums\ShipmentType;
use xGrz\Dhl24\Facades\Config;
use xGrz\Dhl24\Traits\Arrayable;

class ServiceDefinition
{
    use Arrayable;

    public string $product;
    public bool $deliveryEvening = false;
    public bool $deliveryOnSaturday = false;
    public bool $pickupOnSaturday = false;
    public bool $collectOnDelivery = false;
    public ?float $collectOnDeliveryValue = null;
    public ?string $collectOnDeliveryForm = null;
    public ?string $collectOnDeliveryReference = null;
    public bool $insurance = false;
    public ?float $insuranceValue = null;
    public bool $returnOnDelivery = false;
    public ?string $returnOnDeliveryReference = null;
    public bool $proofOfDelivery = false;
    public bool $selfCollect = false;
    public bool $deliveryToNeighbour = false;
    public bool $predeliveryInformation = false;
    public bool $preaviso = false;


    public function __construct(ShipmentType $shipmentType)
    {
        $this->product = $shipmentType->value;
    }

    public function setCollectOnDelivery(int|float $amount, ?string $reference = null): static
    {
        $this->collectOnDelivery = true;
        $this->collectOnDeliveryValue = round($amount, 2);
        $this->collectOnDeliveryReference = $reference;
        $this->collectOnDeliveryForm = 'BANK_TRANSFER';
        $this->setInsurance($amount);
        return $this;
    }

    public function setInsurance(int|float $amount): static
    {
        $amount = self::updateInsuranceValueWithCodValue($amount);
        $amount = self::useIntelligentCostSaver($amount);
        if ($amount) {
            $this->insurance = true;
            $this->insuranceValue = (int)ceil($amount);
        }
        return $this;
    }

    public function setEveningDelivery(bool $evening = true): ServiceDefinition
    {
        $this->deliveryEvening = $this->deliveryOnSaturday ? false : $evening;
        return $this;
    }

    public function setDeliveryOnSaturday(bool $deliveryOnSaturday = true): ServiceDefinition
    {
        if ($deliveryOnSaturday) $this->deliveryEvening = false;
        $this->deliveryOnSaturday = $deliveryOnSaturday;
        return $this;
    }

    public function setPickupOnSaturday(bool $saturdayPickup = true): ServiceDefinition
    {
        $this->pickupOnSaturday = $saturdayPickup;
        return $this;
    }

    public function setReturnOnDelivery(?string $nameOfDocument = null, bool $rod = true): ServiceDefinition
    {
        $this->returnOnDelivery = $rod;
        $rod
            ? $this->returnOnDeliveryReference = $nameOfDocument
            : $this->returnOnDeliveryReference = null;

        return $this;
    }

    public function setProofOfDelivery(bool $pod = true): ServiceDefinition
    {
        $this->proofOfDelivery = $pod;
        return $this;
    }

    public function setSelfCollect(bool $selfCollect = true): ServiceDefinition
    {
        $this->selfCollect = $selfCollect;
        return $this;
    }

    public function setDeliveryToNeighbour(bool $deliveryToNeighbour = true): ServiceDefinition
    {
        $this->deliveryToNeighbour = $deliveryToNeighbour;
        return $this;
    }

    public function setPreDeliveryInformation(bool $pdi = true): ServiceDefinition
    {
        $this->predeliveryInformation = $pdi;
        return $this;
    }

    public function setPreaviso(bool $preaviso = true): ServiceDefinition
    {
        $this->preaviso = $preaviso;
        return $this;
    }

    private function updateInsuranceValueWithCodValue(int|float $amount): float|int
    {
        return max([$amount, $this->insuranceValue, $this->collectOnDeliveryValue]);
    }

    private function useIntelligentCostSaver(int|float $amount): float|int
    {
        if (!Config::shouldUseIntelligentCostSaver()) return $amount;
        if (Config::getIntelligentCostSaverMaxValue() < $amount) return $amount;
        if ($this->collectOnDelivery) return $amount;
        return 0;
    }


}

