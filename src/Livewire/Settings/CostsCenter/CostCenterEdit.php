<?php

namespace xGrz\Dhl24\Livewire\Settings\CostsCenter;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostCenterEdit extends ModalComponent
{

    public ?DHLCostCenter $costCenter = null;
    public string $name = '';


    public function mount(DHLCostCenter $costCenter = null): void
    {
        $this->costCenter = $costCenter;
        $this->name = $costCenter->name;
    }

    public function render(): View
    {
        return view('dhl::settings.livewire.costs-center.cost-center-edit', [
            'title' => 'Edit ' . $this->costCenter->name,
        ]);
    }

    public function update(): void
    {
        $this->validate();
        $this->costCenter->update([
            'name' => $this->name,
        ]);
        $this->closeModal();
        session()->flash('success', 'Cost center has been updated successfully.');
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('dhl_cost_centers', 'name')->ignore($this->costCenter)->whereNull('deleted_at'),
            ],
        ];
    }


}
