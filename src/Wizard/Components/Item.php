<?php

namespace xGrz\Dhl24\Wizard\Components;

use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Traits\Arrayable;

class Item
{
    use Arrayable;

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

    public function setQuantity(int $quantity = 1): Item
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setWidth(int $width): Item
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight(int $height): Item
    {
        $this->height = $height;
        return $this;
    }

    public function setLength(int $length): Item
    {
        $this->length = $length;
        return $this;
    }

    public function setWeight(float $weight): Item
    {
        $this->weight = $weight;
        return $this;
    }
}
