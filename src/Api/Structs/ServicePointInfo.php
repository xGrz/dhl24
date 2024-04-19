<?php

namespace xGrz\Dhl24\Api\Structs;

use xGrz\Dhl24\Api\Structs\ServicePoint\Address;
use xGrz\Dhl24\Api\Structs\ServicePoint\WorkingHours;
use xGrz\Dhl24\Enums\ServicePointType;

class ServicePointInfo
{
    public ServicePointType $type;
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
        $this->type = ServicePointType::tryFrom($servicePoint->type);
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
