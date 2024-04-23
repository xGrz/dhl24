<x-p::table.row>
    <x-p::table.cell>{{ $index  +1 }}</x-p::table.cell>
    <x-p::table.cell>
        <select wire:model.live.debounce.200ms="item.type">
            @foreach($shipmentTypes as $shipmentType)
                <option value="{{$shipmentType->name}}">{{$shipmentType->value}}</option>
            @endforeach
        </select>
    </x-p::table.cell>
    <x-p::table.cell>
        <a href="#" wire:click="addQuantity()">
            <x-p::icons.add-circle class="inline text-green-500 w-6 h-6"/>
        </a>
    </x-p::table.cell>
    <x-p::table.cell>
        {{$item->quantity}}
    </x-p::table.cell>
    <x-p::table.cell>
        <a href="#" wire:click="removeQuantity()">
            <x-p::icons.remove-circle class="inline text-red-500 w-6 h-6"/>
        </a>
    </x-p::table.cell>
    <x-p::table.cell>
        @if($item->weight)
            <input type="number" step="1" value="{{$item->weight}}"/>
        @endif
    </x-p::table.cell>
    <x-p::table.cell>
        @if($item->width)
            <input type="number" step="1" value="{{$item->width}}"/>
        @endif
        @if($item->height)
            <input type="number" step="1" value="{{$item->height}}"/>
        @endif
        @if($item->length)
            <input type="number" step="1" value="{{$item->length}}"/>
        @endif
    </x-p::table.cell>
    <x-p::table.cell>
        <input type="checkbox" @if($item->nonStandard) checked @endif>
    </x-p::table.cell>
    <x-p::table.cell>
        <x-p::button
            type="button"
            size="small"
            color="danger"
            wire:click="delete"
        >
            Delete {{$index}}
        </x-p::button>
    </x-p::table.cell>
</x-p::table.row>
