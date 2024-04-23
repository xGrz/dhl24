<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Livewire\Model\Package;

class ShipmentItem extends Component
{
    public ?int $index;
    public Package $item;

    public function mount(Package $item, int $index): void
    {
        $this->index = $index;
        $this->item = $item;
    }

    public function render(): View
    {
        return view('dhl::livewire.shipment-item', [
            'index' => $this->index,
            'package' => $this->item,
            'shipmentTypes' => ShipmentItemType::cases()
        ]);
    }

    public function updating($prop, $value): void
    {
        if ($prop === 'item.type') {
            self::setShipmentType(ShipmentItemType::findByName($value));
        }
    }


    private function setShipmentType(ShipmentItemType $shipmentType): void
    {
        $this->item->setShipmentType($shipmentType);
    }

    public function delete(): void
    {
        $this->dispatch('delete-item', $this->index);
    }

}
