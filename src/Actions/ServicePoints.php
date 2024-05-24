<?php

namespace xGrz\Dhl24\Actions;

use Illuminate\Support\Collection;
use xGrz\Dhl24\APIStructs\ServicePointInfo;
use xGrz\Dhl24\Enums\DHLServicePointType;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class ServicePoints extends ApiCalls
{
    protected string $method = 'GetNearestServicePoints';
    protected array $payload = [
        'structure' => [
            'country' => 'PL',
            'postcode' => '',
            'radius' => 5,
        ]
    ];
    protected Collection $response;

    public function setCountry($country = 'PL'): static
    {
        $this->payload['country'] = $country;
        return $this;
    }

    public function setPostalCode(string $postalCode): static
    {
        $postalCode = str($postalCode)->replace('-', '')->toString();
        $this->payload['structure']['postcode'] = $postalCode;
        return $this;
    }

    public function setRadius(int $radius): static
    {
        $this->payload['radius'] = $radius;
        return $this;
    }

    /**
     * @throws DHL24Exception
     */
    public function get(?DHLServicePointType $type = null): Collection
    {
        $this->response = new Collection();
        $points = $this->call()?->getNearestServicepointsResult;
        if ($points?->points?->item) {
            foreach ($points->points->item as $point) {
                if (!$type) {
                    $this->response->push(new ServicePointInfo($point));
                } else {
                    if ($point->type === $type->value) {
                        $this->response->push(new ServicePointInfo($point));
                    }
                }
            }
        }
        return $this->response;
    }


}
