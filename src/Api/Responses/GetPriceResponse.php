<?php

namespace xGrz\Dhl24\Api\Responses;

class GetPriceResponse
{
    // expected 47,256 netto
    private float|int $price = 0;
    private float|int $fuelSurcharge = 0;
    private float|int $total = 0;


    public function __construct(object $result)
    {
        $this->price = $result->getPriceResult->price;
        $this->fuelSurcharge = $result->getPriceResult->fuelSurcharge;
        self::calculateTotalPrice();
    }

    public function getPrice(float|int $vatRate = null): float|int
    {
        $vatAmount = $vatRate
            ? self::calculateVatAmount($vatRate)
            : 0;

        return $this->total + $vatAmount;
    }

    private function calculateTotalPrice(): void
    {
        $this->total = round($this->price + $this->price * ($this->fuelSurcharge / 100), 2);
    }

    private function calculateVatAmount(float|int $vatRate): float|int
    {
        return round($this->total * ($vatRate / 100), 2);
    }
}
