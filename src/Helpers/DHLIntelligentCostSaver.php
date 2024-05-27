<?php

namespace xGrz\Dhl24\Helpers;

use xGrz\Dhl24\Facades\DHLConfig;
use xGrz\Dhl24\Models\DHLShipment;

class DHLIntelligentCostSaver
{
    private function __construct(public DHLShipment $shipment, int|float $value = null, int|float $cod = null)
    {
        if ($cod) self::setCod($cod);
        if ($value) self::setInsurance($value);
    }

    private function setInsurance(int|float|null $amount, bool $force = false): void
    {
        $amount = self::getCod() > $amount
            ? self::getCod()
            : $amount;

        if (DHLConfig::shouldUseIntelligentCostSaver() && !$force) {
            $this->shipment->insurance = $amount > DHLConfig::getIntelligentCostSaverMaxValue()
                ? $amount
                : self::getCod();
        } else {
            $this->shipment->insurance = $amount;
        }

        if ($rounding = DHLConfig::getShipmentInsuranceValueRounding()) {
            $this->shipment->insurance = ceil($amount / $rounding) * $rounding;
        }
    }

    private function setCod(int|float|null $amount): void
    {
        $this->shipment->collect_on_delivery = $amount;
        if (self::getInsurance() < $amount) self::setInsurance($amount, true);

    }

    private function getInsurance()
    {
        return $this->shipment->insurance;
    }

    private function getCod()
    {
        return $this->shipment->collect_on_delivery;
    }

    public static function apply(DHLShipment $shipment, int|float $value = null, int|float $cod = null): static
    {
        return new static($shipment, $value, $cod);
    }

}
