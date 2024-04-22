<x-p::table.row>
    <x-p::table.cell>{{ $index  +1 }}</x-p::table.cell>
    <x-p::table.cell>
        <select wire:model="type" wire:change="$refresh">
            @foreach($shipmentTypes as $shipmentType)
                <option value="{{$shipmentType->name}}">{{$shipmentType->value}}</option>
            @endforeach
        </select>
    </x-p::table.cell>
    <x-p::table.cell>
        <input type="number" step="1" value="{{$quantity}}"/>
    </x-p::table.cell>
    <x-p::table.cell>
        @if(!is_null($weight))
            <input type="number" step="1" value="{{$weight}}"/>
        @endif
        @if(!is_null($length))
            <input type="number" step="1" value="{{$length}}"/>
        @endif
        @if(!is_null($width))
            <input type="number" step="1" value="{{$width}}"/>
        @endif
        @if(!is_null($height))
            <input type="number" step="1" value="{{$height}}"/>
        @endif
    </x-p::table.cell>
    <x-p::table.cell>
        @if(!is_null($nonStandard))
            <input type="checkbox" value="{{$nonStandard}}"/>
        @endif
    </x-p::table.cell>
    <x-p::table.cell>
        <x-p::button
            type="button"
            size="small"
            color="danger"
            wire:click="delete"
{{--            wire:confirm="Are you sure you want to delete this post?"--}}
        >
            Delete {{$index}}
        </x-p::button>
    </x-p::table.cell>
</x-p::table.row>
