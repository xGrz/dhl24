<?php

namespace xGrz\Dhl24\Api\Actions;

use Illuminate\Support\Carbon;
use xGrz\Dhl24\Api\Structs\AuthData;

class GetPostalCodeServices extends BaseApiAction
{
    public AuthData $authData;
    public string $postCode;
    public string $pickupDate;

    private function __construct(string $postCode, Carbon $pickupDate = null)
    {
        $this->setPostCode($postCode);
        $this->pickupDate = $pickupDate
            ? $pickupDate->format('Y-m-d')
            : now()->format('Y-m-d');
    }

    private function setPostCode(string $postCode): static
    {
        $this->postCode = preg_replace('/[^0-9]/', '', $postCode);
        return $this;
    }

    public static function make(string $postCode, Carbon $pickupDate = null): GetPostalCodeServices
    {
        return new self($postCode, $pickupDate);
    }
}
