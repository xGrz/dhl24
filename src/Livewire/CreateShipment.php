<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;
use xGrz\Dhl24\Enums\ShipmentType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Http\Requests\StoreShipmentRequest;
use xGrz\Dhl24\Livewire\Forms\ShipmentContactForm;
use xGrz\Dhl24\Livewire\Forms\ShipmentRecipientForm;
use xGrz\Dhl24\Wizard\ShipmentWizard;

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
        return view('dhl::shipments.livewire.create-shipment', [
            'shipmentTypes' => ShipmentItemType::cases()
        ]);
    }

    public function createPackage(): void
    {
        $this->validate();
        $wizard = new ShipmentWizard(ShipmentType::DOMESTIC);
        $wizard->shipper()
            ->setName('BRAMSTAL')
            ->setStreet('Sęczkowa')
            ->setHouseNumber('96')
            ->setPostalCode('03986')
            ->setCity('Warszawa')
            ->setContactEmail('biuro@bramstal.pl')
            ->setContactPhone('501335555');
        $wizard->receiver()
            ->setName($this->recipient->name)
            ->setStreet($this->recipient->street)
            ->setHouseNumber($this->recipient->houseNumber)
            ->setPostalCode($this->recipient->postalCode)
            ->setCity($this->recipient->city)
            ->setContactEmail($this->contact->email ?? null)
            ->setContactPhone($this->contact->phone ?? null);

        foreach ($this->items as $item) {
            $wizard
                ->addItem(ShipmentItemType::findByName($item['type']))
                ->setQuantity($item['quantity'])
                ->setWeight($item['weight'] ?? null)
                ->setWidth($item['width'] ?? null)
                ->setHeight($item['height'] ?? null)
                ->setLength($item['length'] ?? null)//->setNonStandard($item['nonStandard'] ?? false)
            ;

        }
        // dd($wizard->toArray());
        dd(DHL24::getPriceOptions($wizard));
    }

    public function addItem(): void
    {
        $this->items[] = self::getItemDefinition(ShipmentItemType::PACKAGE);
    }

    public function updatedRecipientPostalCode($value): void
    {
        $this->dispatch('postalCode-updated', $value);
    }

    public function changeShipmentType(ShipmentItemType $type): array
    {
        return self::getItemDefinition($type);
    }

    public function updatedItems(mixed $value, string $arrayKey): void
    {
        [$key, $prop] = explode('.', $arrayKey);
        if ($prop === 'type') {
            $this->items[$key] = self::changeShipmentType(ShipmentItemType::findByName($value));
        }
        self::shouldBeNonStandard($this->items[$key]);
        $this->validate();
    }

    public function removePackage(int $index): void
    {
        if (count($this->items) < 2) return;
        unset($this->items[$index]);
    }

    private function shouldBeNonStandard(array &$item): void
    {
        $shouldBeNonStandard = false;
        if (isset($item['width']) && $item['width'] > 120) $shouldBeNonStandard = true;
        if (isset($item['height']) && $item['height'] > 120) $shouldBeNonStandard = true;
        if (isset($item['length']) && $item['length'] > 120) $shouldBeNonStandard = true;
        $item['shouldBeNonStandard'] = $shouldBeNonStandard;
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
        if (in_array('nonStandard', $attributes)) {
            $item['nonStandard'] = false;
            $item['shouldBeNonStandard'] = false;
        }
        return $item;
    }

}
