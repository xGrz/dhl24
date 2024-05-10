<x-p-paper>
    <x-slot:title>New cost center</x-slot:title>
    <form wire:submit="store">
        <div class="p-2">
            <x-p-input label="Name" wire:model.live.debounce.300ms="name"/>
            <div class="mt-4 text-right">
                <x-p-button type="submit" color="primary" size="large">Create</x-p-button>
            </div>
        </div>
    </form>
</x-p-paper>
