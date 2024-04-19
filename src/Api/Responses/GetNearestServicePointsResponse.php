<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Support\Collection;
use xGrz\Dhl24\Api\Structs\ServicePointInfo;
use xGrz\Dhl24\Enums\ServicePointType;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class GetNearestServicePointsResponse
{
    private Collection $points;

    private array $pointsByType = [];

    /**
     * @throws DHL24Exception
     */
    public function __construct(object $result)
    {
        $this->points = new Collection();
        $points = $result->getNearestServicepointsResult->points->item;
        foreach ($points as $point) {
            $this->points->push(new ServicePointInfo($point));
        }
    }

    public function getPointsByType(ServicePointType $pointType, ?int $maxResultCount = null): Collection
    {
        if (array_key_exists($pointType->name, $this->pointsByType)) {
            $points = collect($this->pointsByType[$pointType->name]);
        } else {
            $this->pointsByType[$pointType->name] = $this->points->filter(function (ServicePointInfo $point) use ($pointType) {
                return $point->type === $pointType;
            });
            $points = $this->pointsByType[$pointType->name];
        }
        return $maxResultCount ? $points->take($maxResultCount) : $points;

    }

    public function getPoints(int $max = null): Collection
    {
        // todo: ByType
        return $max
            ? $this->points->take($max)
            : $this->points;
    }

    public function toArray()
    {
        return json_decode(json_encode($this->points->toArray()), true);
    }


}
