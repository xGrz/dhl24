<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class ShippingCostCenterEdit extends ModalComponent
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
            'title' => $this->form->costCenter->exists
                ? 'Edit ' . $this->form->costCenter->name
                : 'Add cost center',
            'action' => $this->form->costCenter->exists
                ? 'Save changes'
                : 'Create cost center',
        ]);
    }

    public function save()
    {
        // $this->validate();
        $this->form->store();
        $this->closeModal();
        $this->dispatch('refresh-cost-centers-list');
    }

}
