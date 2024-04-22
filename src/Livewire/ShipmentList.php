<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;

class ShipmentList extends Component
{

    public array $items;

    public function mount(): void
    {
        self::addPackage();
    }

    public function render(): View
    {
        return view('dhl::livewire.shipment-list', [
            'types' => ShipmentItemType::cases(),
        ]);
    }

    public function addPackage(): void
    {
        $this->items[] = true;
    }

    #[On('delete-item')]
    public function removePackage(int $index): void
    {
        unset($this->items[$index]);
    }

}
