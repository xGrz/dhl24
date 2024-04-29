<?php

namespace xGrz\Dhl24\Livewire;

use Livewire\Component;
use xGrz\Dhl24\Models\DHLContentSuggestion;

class ShippingContents extends Component
{
    public $contents = null;

    public function mount()
    {
        $this->contents = DHLContentSuggestion::orderBy('content')
            ->get();
    }

    public function render()
    {
        return view('dhl::settings.livewire.shipping-contents');
    }
}
