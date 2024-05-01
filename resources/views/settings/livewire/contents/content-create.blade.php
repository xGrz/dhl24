<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="New content suggestion"/>
    <form wire:submit="store">
        <div class="p-2">
            <x-p-textinput label="Suggestion name" wire:model.live.debounce.300ms="name"/>
            <div class="mt-4 text-right">
                <x-p::button type="submit" color="primary" size="large">Create</x-p::button>
            </div>
        </div>
    </form>
</x-p::paper>
