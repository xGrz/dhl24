<?php

namespace xGrz\Dhl24\Livewire\Settings\CostsCenter;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostCenterDelete extends ModalComponent
{
    public ?DHLCostCenter $costCenter = null;

    public function mount(DHLCostCenter $costCenter = null): void
    {
            $this->costCenter = $costCenter;
    }

    public function render(): View
    {
        return view('dhl::settings.livewire.costs-center.cost-center-delete');
    }

    public function deleteConfirmed(): void
    {
        $this->costCenter->delete();
        $this->closeModal();
        session()->flash('success', 'Cost center has been deleted.');
        $this->redirect(route('dhl24.costCenters.index'));
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

}
