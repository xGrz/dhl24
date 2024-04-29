<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="New cost center"/>
    <form wire:submit="store">
        <div class="p-2">
            <x-p::input label="Cost center name" wire:model.live.debounce.300ms="name"/>
            <div class="mt-2 text-right">
                <x-p::button type="submit" color="primary" size="large">Create</x-p::button>
            </div>
        </div>
    </form>
</x-p::paper>
