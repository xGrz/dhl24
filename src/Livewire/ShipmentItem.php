<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;

class ShipmentItem extends Component
{
    public ShipmentItemType $shipmentType;
    public ?string $type;
    public ?int $quantity = null;
    public ?int $weight = null;
    public ?int $length = null;
    public ?int $width = null;
    public ?int $height = null;
    public array $shipmentTypes = [];
    public ?bool $nonStandard = null;
    public ?int $index = null;

    public function mount($index): void
    {
        $this->index = $index;
        self::setShipmentType(ShipmentItemType::ENVELOPE);
        $this->shipmentTypes = ShipmentItemType::cases();
    }

    public function render(): View
    {
        return view('dhl::livewire.shipment-item');
    }

    public function updatedType(): void
    {
        self::setShipmentType(ShipmentItemType::findByName($this->type));
    }


    private function setShipmentType(ShipmentItemType $shipmentType): void
    {
        $this->shipmentType = $shipmentType;
        $this->type = $shipmentType->name;

        $parameters = $shipmentType->getAttributes();

        $this->width = in_array('width', $parameters) ? 10 : null;
        $this->height = in_array('height', $parameters) ? 10 : null;
        $this->length = in_array('length', $parameters) ? 10 : null;
        $this->weight = in_array('weight', $parameters) ? 1 : null;
        $this->nonStandard = in_array('nonStandard', $parameters) ? false : null;
    }

    public function delete(): void
    {
        $this->dispatch('delete-item', $this->index);
    }

}
