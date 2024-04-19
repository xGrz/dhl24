<?php

namespace xGrz\Dhl24\Api\Structs\Shipment;

use xGrz\Dhl24\Enums\ShipmentItemType;

class Item
{
    public string $type;
    public ?int $width = null;
    public ?int $height = null;
    public ?int $length = null;
    public ?float $weight = null;
    public int $quantity = 1;
    public ?bool $nonStandard = null;
    public ?bool $euroReturn = null;

    public function __construct(ShipmentItemType $type = ShipmentItemType::PACKAGE)
    {
        $this->type = $type->name;
    }

    public function setDiamentions(int $width = null, int $height = null, int $length = null, float $weight = null): Item
    {
        $this->width = $width;
        $this->height = $height;
        $this->length = $length;
        $this->weight = $weight;
        return $this;
    }

    public function setNonStandard(): Item
    {
        $this->nonStandard = true;
        return $this;
    }

    public function setEuroReturn(): Item
    {
        $this->euroReturn = true;
        return $this;
    }
}
