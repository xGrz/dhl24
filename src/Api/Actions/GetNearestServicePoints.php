<?php

namespace xGrz\Dhl24\Api\Actions;

use xGrz\Dhl24\Api\Structs\AuthData;

class GetNearestServicePoints extends BaseApiAction
{
    public AuthData $authData;

    public array $structure = [
        'country' => 'PL',
        'postcode' => '',
        'radius' => 5
    ];

    private function __construct(string $postcode, int $radius = 5, string $countyCode = 'PL')
    {
        $this->setPostCode($postcode);
        $this->structure['country'] = $countyCode;
        $this->structure['radius'] = $radius;
    }

    private function setPostCode(string $postCode): static
    {
        $this->structure['postcode'] = preg_replace('/[^0-9]/', '', $postCode);
        return $this;
    }

    public static function make(string $postcode, int $radius = 5, string $countyCode = 'PL'): static
    {
        return new static($postcode, $radius, $countyCode);
    }
}
