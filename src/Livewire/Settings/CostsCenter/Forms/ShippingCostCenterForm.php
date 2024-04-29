<?php

namespace xGrz\Dhl24\Livewire\Settings\CostsCenter\Forms;

use Illuminate\Validation\Rule;
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


    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('dhl_cost_centers', 'name')->ignore($this->component->form->costCenter)->whereNull('deleted_at'),
            ],
        ];
    }

}
