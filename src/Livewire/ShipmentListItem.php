<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use xGrz\Dhl24\Enums\ShipmentItemType;

class ShipmentListItem extends Component
{
    public ?int $index;

    public string|null $type = null;
    #[Validate('required|numeric|max:99', message: 'Ups')]
    public string|null $quantity = '1';

    #[Validate('nullable|integer|max_digits:3|max:')]
    public string|null $weight = null;

    #[Validate('nullable|integer|max_digits:3')]
    public string|null $width = null;

    #[Validate('nullable|integer|max_digits:3')]
    public string|null $height = null;

    #[Validate('nullable|integer|max_digits:3')]
    public string|null $length = null;

    #[Validate('nullable|boolean')]
    public bool|null $nonStandard = null;

    public bool $shouldBeNonStandard = false;

    public function mount(array $item, int $index): void
    {
        $this->index = $index;
        foreach ($item as $key => $value) {
            $this->$key = $value;
        }
    }

    public function render(): View
    {
        return view('dhl::livewire.shipment-list-item', [
            'shipmentTypes' => ShipmentItemType::cases()
        ]);
    }

    public function delete(): void
    {
        $this->dispatch('delete-item', $this->index);
    }

    public function updatingType($shipmentTypeName): void
    {
        $shipmentType = ShipmentItemType::findByName($shipmentTypeName);
        $this->reset();
        $this->type = $shipmentType->name;

        $this->setValue('weight', 1)
            ->setValue('width', 15)
            ->setValue('height', 20)
            ->setValue('length', 25)
            ->setValue('nonStandard', false);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updating($propertyName, $propertyValue): void
    {
        $nonStandardWatcher = ['width', 'height', 'length'];
        if (in_array($propertyName, $nonStandardWatcher) && (is_numeric($propertyValue))) {
            $this->shouldBeNonStandard = $propertyValue > 100;
        }

    }

    public function updated()
    {
        $this->dispatch('update-item', $this->index, $this);
    }

    private function setValue(string $prop, mixed $defaultValue): static
    {
        $shipmentAttributes = ShipmentItemType::findByName($this->type)->getAttributes();
        $this->$prop = in_array($prop, $shipmentAttributes)
            ? $defaultValue
            : null;
        return $this;
    }

}
