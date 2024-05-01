<?php

namespace xGrz\Dhl24\Livewire\Settings\Contents;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLContentSuggestion;

class ContentDelete extends ModalComponent
{
    public DHLContentSuggestion $content;

    public function mount(DHLContentSuggestion $contentSuggestion): void
    {
        $this->content = $contentSuggestion;
    }


    public function render(): View
    {
        return view('dhl::settings.livewire.contents.content-delete');
    }

    public function deleteConfirmed(): void
    {
        $this->content->delete();
        $this->closeModal();
        session()->flash('success', 'Content suggestion has been deleted.');
        $this->redirect(route('dhl24.settings.index'));
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

}
