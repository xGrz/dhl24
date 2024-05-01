<?php

namespace xGrz\Dhl24\Livewire\Settings\Contents;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Models\DHLContentSuggestion;

class ContentsListing extends Component
{

    public $contents;

    public function mount()
    {
        $this->contents = DHLContentSuggestion::orderBy('name')->get();
    }

    public function render(): View
    {
        return view('dhl::settings.livewire.contents.contents-listing');
    }

    public function setAsDefault(int $itemId): void
    {
        $this->contents->find($itemId)->update(['is_default' => true]);
        session()->flash('success', 'Default content set.');
        $this->redirectRoute('dhl24.contents.index');
    }

    public function removeDefault(int $itemId): void
    {
        $this->contents->find($itemId)->update(['is_default' => false]);
        session()->flash('info', 'Default content removed.');
        $this->redirectRoute('dhl24.contents.index');
    }
}
