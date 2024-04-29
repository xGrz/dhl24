<?php

namespace xGrz\Dhl24\Livewire\Settings\CostsCenter;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostCenterCreate extends ModalComponent
{
    #[Validate]
    public string $name;

    public function render(): View
    {
        return view('dhl::settings.livewire.shipping-cost-center-create');
    }

    public function store(): void
    {
        $this->validate();
        DHLCostCenter::create(['name' => $this->name]);
        $this->closeModal();
        $this->dispatch('refresh-cost-centers-list');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'unique:dhl_cost_centers,name',
                Rule::unique('dhl_costs_center', 'name')->whereNull('deleted_at')
            ],
        ];
    }

}