<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="Packages">
        <button type="button" class="text-green-500" wire:click="addItem()">
            <x-p::icons.add-circle/>
        </button>
    </x-p::paper-title>

    @if($items)
        @foreach($items as $key => $item)
            <livewire:shipment-item
                    wire:key="item_{{$key}}"
                    index="{{$key}}"
                    :$item
            />
        @endforeach
    @else
        <x-p::not-found message="Packages not found"/>
    @endif

    <div class="text-center mr-2 mt-3 pt-1 pb-4">
        <x-p::link type="button" wire:click.prevent="addItem()">
            Add package ({{$items_count}})
        </x-p::link>
    </div>

</x-p::paper>

