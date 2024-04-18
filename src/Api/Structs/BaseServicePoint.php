<?php

namespace xGrz\Dhl24\Api\Structs;

abstract class BaseServicePoint
{
    public string $type;
    public string $name;
    public string $fullAddress;
    public ?string $description = null;
    public ParcelPointAddress $address;
    public string $longitude;
    public string $latitude;
    public int $sap;
    public bool $workInHoliday = false;
    public ParcelPointWorkingHours $workingHours;
    public bool $isNonstopOpen = false;

    public function __construct(object $servicePoint)
    {
        $this->type = $servicePoint->type;
        $this->name = $servicePoint->name;
        $this->description = $servicePoint->description;
        $this->address = new ParcelPointAddress($servicePoint->address, $servicePoint->name);
        $this->fullAddress = $this->address->getFullAddress();
        $this->workingHours = new ParcelPointWorkingHours($servicePoint);
        $this->longitude = $servicePoint->longitude;
        $this->latitude = $servicePoint->latitude;
        $this->sap = $servicePoint->sap;
        $this->workInHoliday = $servicePoint->workInHoliday;
        $this->isNonstopOpen = $this->workingHours->isNonstopOpen() && $servicePoint->workInHoliday;
    }

}
