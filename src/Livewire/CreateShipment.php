<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;
use xGrz\Dhl24\Livewire\Forms\ShipmentContactForm;
use xGrz\Dhl24\Livewire\Forms\ShipmentRecipientForm;

class CreateShipment extends Component
{
    public ShipmentRecipientForm $recipient;
    public ShipmentContactForm $contact;

    public Collection $items;

    public function mount(): void
    {
        self::addItem();
    }

    public function rules(): array
    {
        return (new StoreShipmentRequest())->rules();
    }

    public function render(): View
    {
        return view('dhl::livewire.create-shipment');
    }

    public function createPackage()
    {
        $this->validate();
        dd('ok');
    }

    public function addItem(): void
    {
        $this->packages->addItem();
    }

//    #[On('delete-item')]
//    public function removePackage(int $index): void
//    {
//        if (count($this->items) < 2) return;
//        unset($this->items[$index]);
//    }

}
