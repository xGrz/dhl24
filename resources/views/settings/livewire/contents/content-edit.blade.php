<x-p-paper>
    <x-slot:title>{{$title}}</x-slot:title>
    <form wire:submit="update">
        <div class="p-2">
            <x-p-input label="Suggestion" wire:model.live.debounce.300ms="name"/>
            <div class="mt-4 text-right">
                <x-p-button type="submit" color="primary" size="large">Save changes</x-p-button>
            </div>
        </div>
    </form>
</x-p-paper>
