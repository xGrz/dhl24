<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="{{ $title }}"/>
    <form wire:submit="update">
        <div class="p-2">
            <x-p::input label="Name" wire:model.live.debounce.300ms="form.name"/>
            <div class="mt-2 text-right">
                <x-p::button type="submit" color="primary" size="large">Save changes</x-p::button>
            </div>
        </div>
    </form>
</x-p::paper>
