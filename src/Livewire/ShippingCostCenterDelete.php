<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class ShippingCostCenterDelete extends ModalComponent
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
        return view('dhl::settings.livewire.shipping-cost-center-delete');
    }

    public function deleteConfirmed(): void
    {
        $this->form->store();
        $this->closeModal();
        $this->dispatch('refresh-cost-centers-list');
    }

    public function cancel()
    {
        $this->closeModal();
        // $this->dispatch('refresh-cost-centers-list');
    }

}
