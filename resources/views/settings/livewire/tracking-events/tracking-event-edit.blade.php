<x-p-paper>
    <x-slot:title>{{$title}}</x-slot:title>
    <form wire:submit="update">
        <div class="grid gap-2 p-2">
            <p>
                <small class="block text-slate-500">DHL system description:</small>
                <span class="italic">{{$description}}</span>
            </p>
            <x-p-input label="Your description" wire:model.live.debounce.300ms="custom_description"/>
            <x-p-select wire:model.live="type" label="Stan dostawy">
                @foreach($types as $ident => $typeName)
                    <option value="{{$ident}}">{{$typeName}}</option>
                @endforeach
            </x-p-select>

            <div class="mt-4 text-right">
                <x-p-button type="submit" color="primary" size="large">Save changes</x-p-button>
            </div>
        </div>
    </form>
</x-p-paper>
