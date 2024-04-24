<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;

class ShipmentList extends Component
{

    public Collection $items;

    public function mount(): void
    {
        $this->items = new Collection();
        self::addItem();
    }

    public function render(): View
    {
        return view('dhl::livewire.shipment-list', [
            'items_count' => count($this->items),
        ]);
    }

    public function addItem(): void
    {
        $attributes = ShipmentItemType::PACKAGE->getAttributes();
        $package = collect([
            'type' => ShipmentItemType::PACKAGE->name,
            'quantity' => 1
        ]);
        if (in_array('weight', $attributes)) $package->put('weight', 1);
        if (in_array('width', $attributes)) $package->put('width', 15);
        if (in_array('height', $attributes)) $package->put('height', 10);
        if (in_array('length', $attributes)) $package->put('length', 5);
        if (in_array('nonStandard', $attributes)) $package->put('nonStandard', false);
        $this->items->push($package);
    }

    #[On('delete-item')]
    public function removePackage(int $index): void
    {
        unset($this->items[$index]);
    }

}
