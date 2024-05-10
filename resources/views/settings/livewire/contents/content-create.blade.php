<x-p-paper>
    <x-slot:title>New content suggestion</x-slot:title>
    <form wire:submit="store">
        <div class="p-2">
            <x-p-input label="Suggestion name" wire:model.live.debounce.300ms="name"/>
            <div class="mt-4 text-right">
                <x-p-button type="submit" color="primary" size="large">Create</x-p-button>
            </div>
        </div>
    </form>
</x-p-paper>
