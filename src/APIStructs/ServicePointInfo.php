<?php

namespace xGrz\Dhl24\APIStructs;


use xGrz\Dhl24\APIStructs\ServicePoint\Address;
use xGrz\Dhl24\APIStructs\ServicePoint\WorkingHours;
use xGrz\Dhl24\Enums\DHLServicePointType;

class ServicePointInfo
{
    public DHLServicePointType $type;
    public string $name;
    public string $fullAddress;
    public ?string $description = null;
    public Address $address;
    public string $longitude;
    public string $latitude;
    public int $sap;
    public bool $workInHoliday = false;
    public WorkingHours $workingHours;
    public bool $isNonstopOpen = false;

    public function __construct(object $servicePoint)
    {
        $this->type = DHLServicePointType::tryFrom($servicePoint->type);
        $this->name = $servicePoint->name;
        $this->description = $servicePoint->description;
        $this->address = new Address($servicePoint->address, $servicePoint->name);
        $this->fullAddress = $this->address->getFullAddress();
        $this->workingHours = new WorkingHours($servicePoint);
        $this->longitude = $servicePoint->longitude;
        $this->latitude = $servicePoint->latitude;
        $this->sap = $servicePoint->sap;
        $this->workInHoliday = $servicePoint->workInHoliday;
        $this->isNonstopOpen = $this->workingHours->isNonstopOpen() && $servicePoint->workInHoliday;
    }

}
