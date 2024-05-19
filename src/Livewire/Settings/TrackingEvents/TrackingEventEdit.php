<?php

namespace xGrz\Dhl24\Livewire\Settings\TrackingEvents;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Enums\StatusType;
use xGrz\Dhl24\Models\DHLStatus;

class TrackingEventEdit extends ModalComponent
{
    public DHLStatus $event;
    public $types = [];

    public $description = '';
    #[Validate]
    public $custom_description = '';
    public $type;

    public function mount(DHLStatus $event): void
    {
        $this->types = StatusType::getOptions();
        $this->type = $event->type;
        $this->event = $event;
        $this->description = $event->description;
        $this->custom_description = $event->custom_description;
    }
    public function render(): View
    {
        return view('dhl::settings.livewire.tracking-events.tracking-event-edit',[
            'title' => 'Edit event description',
        ]);
    }

    public function rules(): array
    {
        return [
            'custom_description' => 'string',
            'type' => 'required'
        ];
    }

    public function update(): void
    {
        $this->validate();
        $this->event->update([
            'custom_description' => $this->custom_description,
            'type' => $this->type
        ]);
        $this->closeModal();
        session()->flash('success', 'Tracking event successfully updated.');
        $this->redirectRoute('dhl24.settings.tracking-events.index');
    }

}
