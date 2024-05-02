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
        $this->closeModal();
        $this->costCenter->delete();
        session()->flash('success', 'Cost center has been deleted successfully.');
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

}
