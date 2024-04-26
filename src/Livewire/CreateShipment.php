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
        // self::addItem();
    }

    public function rules(): array
    {
        return (new StoreShipmentRequest())->rules();
    }

    public function render(): View
    {
        return view('dhl::livewire.create-shipment', [
            'shipmentTypes' => ShipmentItemType::cases()
        ]);
    }

    public function createPackage()
    {
        $this->validate();
        dd($this->items);
    }

    public function addItem(): void
    {
        $this->items[] = self::getItemDefinition(ShipmentItemType::PACKAGE);
    }

    public function changeShipmentType(ShipmentItemType $type): array
    {
        return self::getItemDefinition($type);
    }

    private function getItemDefinition(ShipmentItemType $type): array
    {
        $item['type'] = $type->name;
        $item['quantity'] = 1;
        $attributes = $type->getAttributes();
        if (in_array('weight', $attributes)) $item['weight'] = $type->getDefaultWeight();
        if (in_array('width', $attributes)) $item['width'] = $type->getDefaultWidth();
        if (in_array('height', $attributes)) $item['height'] = $type->getDefaultHeight();
        if (in_array('length', $attributes)) $item['length'] = $type->getDefaultLength();
        if (in_array('nonStandard', $attributes)) $item['nonStandard'] = false;
        return $item;
    }

    public function updatedItems(mixed $value, string $arrayKey)
    {
        [$key, $prop] = explode('.', $arrayKey);
        if ($prop === 'type') {
            $this->items[$key] = self::changeShipmentType(ShipmentItemType::findByName($value));
        }
        $this->validate();
    }

    #[On('delete-item')]
    public function removePackage(int $index): void
    {
        if (count($this->items) < 2) return;
        unset($this->items[$index]);
    }

}
