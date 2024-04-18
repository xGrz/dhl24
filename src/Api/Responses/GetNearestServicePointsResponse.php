<?php

namespace xGrz\Dhl24\Api\Responses;

use Illuminate\Support\Collection;
use xGrz\Dhl24\Api\Structs\ParcelShopPoint;
use xGrz\Dhl24\Api\Structs\ParcelStationPoint;
use xGrz\Dhl24\Api\Structs\ServiceStationPoint;
use xGrz\Dhl24\Exceptions\DHL24Exception;

class GetNearestServicePointsResponse
{
    private Collection $points;

    /**
     * @throws DHL24Exception
     */
    public function __construct(object $result)
    {
        $this->points = new Collection();
        $points = $result->getNearestServicepointsResult->points->item;
        foreach ($points as $point) {
            $className = match ($point->type) {
                'PARCELSHOP' => ParcelShopPoint::class,
                'SERVICEPOINT' => ServiceStationPoint::class,
                'PARCELSTATION' => ParcelStationPoint::class,
                default => throw new DHL24Exception('Invalid Parcel point: [' . $point->type . '] for ' . $point->name),
            };
            $this->points->push(new $className($point));
        }

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
