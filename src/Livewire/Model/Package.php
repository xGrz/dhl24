<?php

namespace xGrz\Dhl24\Livewire\Model;

use Illuminate\Support\Collection;
use Livewire\Wireable;
use xGrz\Dhl24\Enums\ShipmentItemType;

class Package implements Wireable
{
    private ShipmentItemType $shipmentType;
    public ?string $type;

    public function __construct(
        ShipmentItemType      $shipmentType = null,
        public int            $quantity = 1,
        public int|float|null $weight = null,
        public int|null       $width = null,
        public int|null       $height = null,
        public int|null       $length = null,
        public bool|null      $nonStandard = null
    )
    {
        self::setShipmentType($shipmentType);
    }

    public function toLivewire(): array
    {
        $livewire = new Collection([
            'type' => $this->type,
            'quantity' => $this->quantity,
        ]);
        if (!is_null($this->weight)) $livewire->put('weight', $this->weight);;
        if (!is_null($this->width)) $livewire->put('width', $this->width);;
        if (!is_null($this->height)) $livewire->put('height', $this->height);;
        if (!is_null($this->length)) $livewire->put('length', $this->length);;
        if (!is_null($this->nonStandard)) $livewire->put('nonStandard', $this->nonStandard);;
        return $livewire->toArray();
    }

    public static function fromLivewire($value): static
    {
        $shipmentType = ShipmentItemType::findByName($value['type']);
        return new static($shipmentType, $value['quantity'], $value['weight'] ?? null, $value['width'] ?? null, $value['height'] ?? null, $value['length'] ?? null, $value['nonStandard'] ?? null);
    }

    public function setShipmentType(ShipmentItemType $shipmentType): void
    {
        $this->shipmentType = $shipmentType;
        $this->type = $shipmentType->name;
        $attributes = $shipmentType->getAttributes();
        $this->weight = in_array('weight', $attributes) ? 1 : null;
        $this->width = in_array('width', $attributes) ? 15 : null;
        $this->height = in_array('height', $attributes) ? 10 : null;
        $this->length = in_array('length', $attributes) ? 5 : null;
        $this->nonStandard = in_array('nonStandard', $attributes) ? false : null;
    }
}
