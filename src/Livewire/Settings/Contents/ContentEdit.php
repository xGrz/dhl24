<?php

namespace xGrz\Dhl24\Livewire\Settings\Contents;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLContentSuggestion;

class ContentEdit extends ModalComponent
{
    public DHLContentSuggestion $content;
    public string $name = '';

    public function mount(DHLContentSuggestion $contentSuggestion): void
    {
        $this->content = $contentSuggestion;
        $this->name = $this->content->name;
    }
    public function render(): View
    {
        return view('dhl::settings.livewire.contents.content-edit',[
            'title' => 'Edit suggestion: ' . $this->content->name,
        ]);
    }

    public function update(): void
    {
        $this->validate();
        $this->closeModal();
        $this->content->update([
            'name' => $this->name,
        ]);
        session()->flash('success', 'Suggestion has been updated.');
        $this->redirectRoute('dhl24.settings.contents.index');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('dhl_contents', 'name')->ignore($this->content),
            ],
        ];
    }

}
