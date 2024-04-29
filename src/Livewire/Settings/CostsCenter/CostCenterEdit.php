<?php

namespace xGrz\Dhl24\Livewire\Settings\CostsCenter;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostCenterEdit extends ModalComponent
{
    public Forms\ShippingCostCenterForm $form;

    public function mount(DHLCostCenter $costCenter = null): void
    {
        if ($costCenter->exists()) {
            $this->form->setCostCenter($costCenter);
        }
    }

    public function render(): View
    {
        return view('dhl::settings.livewire.shipping-cost-center-edit', [
            'title' => 'Edit ' . $this->form->costCenter->name,
        ]);
    }

    public function update(): void
    {
        $this->validate();
        $this->form->costCenter->update([
            'name' => $this->form->name,
        ]);
        $this->closeModal();
        $this->dispatch('refresh-cost-centers-list');
    }


}
