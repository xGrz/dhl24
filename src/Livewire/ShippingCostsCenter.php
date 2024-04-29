<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\Dhl24\Models\DHLCostCenter;

class ShippingCostsCenter extends Component
{
    public $costCenters = null;

    public function mount(): void
    {
        self::loadList();
    }

    public function render(): View
    {
        return view('dhl::settings.livewire.shipping-costs-center');
    }

    public function delete($itemId): void
    {
        DHLCostCenter::destroy($itemId);
        $this->dispatch('refresh-cost-centers-list');
    }

    #[On('refresh-cost-centers-list')]
    public function loadList(): void
    {
        $this->costCenters = DHLCostCenter::orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }


}
