<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Form;
use xGrz\Dhl24\Models\DHLCostCenter;

class ShippingCostCenterForm extends Form
{
    public ?DHLCostCenter $costCenter = null;
    public string $name = '';

    public function setCostCenter(DHLCostCenter $costCenter): void
    {
        $this->costCenter = $costCenter;
        $this->name = $costCenter->name ?? '';
    }

    public function store(): void
    {
        if (empty($this->costCenter)) {
            DHLCostCenter::create($this->only(['name']));
        } else {
            $this->costCenter->update($this->only(['name']));
        }
        $this->reset();
    }


}
