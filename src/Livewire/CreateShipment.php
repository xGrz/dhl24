<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Livewire\Forms\ShipmentContactForm;
use xGrz\Dhl24\Livewire\Forms\ShipmentRecipientForm;

class CreateShipment extends Component
{

    public ShipmentRecipientForm $recipient;
    public ShipmentContactForm $contact;

    public Collection $items;

    public function mount(): void
    {
        $this->items = new Collection();
        self::addItem();
    }

    public function render(): View
    {
        return view('dhl::livewire.create-shipment');
    }

    public function formValidation(): bool
    {
        $errors = [];
        try {
            $this->recipient->validate();
        } catch (ValidationException $exception) {
            foreach ($exception->validator->getMessageBag()->getMessages() as $key => $message)
                $errors[$key] = $message;
        }
        try {
            $this->contact->validate();
        } catch (ValidationException $exception) {
            foreach ($exception->validator->getMessageBag()->getMessages() as $key => $message)
                $errors[$key] = $message;
        }
        foreach ($this->items as $item) {
            dd($item);
        }

        if (count($errors)) {
            $this->setErrorBag(new MessageBag($errors));
            return false;
        }
        return true;
    }

    public function createPackage()
    {
        if (!$this->formValidation()) return;
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
        if ($this->items->count() < 2) return;
        unset($this->items[$index]);
    }

}
