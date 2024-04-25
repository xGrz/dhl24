<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;
use xGrz\Dhl24\Livewire\Forms\ShipmentContactForm;
use xGrz\Dhl24\Livewire\Forms\ShipmentRecipientForm;

class CreateShipment extends Component
{
    public ShipmentRecipientForm $recipient;
    public ShipmentContactForm $contact;

    #[Validate]
    public array $items = [];

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

//    public function updated(): void
//    {
//        $this->validate();
//    }

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
        $this->items[] = $package->toArray();
    }

    #[On('delete-item')]
    public function removePackage(int $index): void
    {
        if (count($this->items) < 2) return;
        unset($this->items[$index]);
    }

}
