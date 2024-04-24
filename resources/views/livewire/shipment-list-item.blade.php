<div class="grid grid-cols-9 gap-6 items-center py-4 border-b border-slate-700">
    <div class="col-span-2 mx-2 flex flex-col">
        <div
            class="items-center text-center text-xs uppercase bg-slate-400 text-slate-700 font-bold rounded-t-md">
            Shipment type
        </div>
        <select
            wire:model.live.debounce.200ms="item.type"
            class="px-2 py-1 w-full text-slate-800 bg-slate-300 focus:bg-gray-100 rounded-b-md"
        >
            @foreach($shipmentTypes as $shipmentType)
                <option value="{{$shipmentType->name}}">{{$shipmentType->value}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-span-1">
        <x-dhl::diamentions value="{{$item->quantity}}" label="Quantity" model="item.quantity"/>
    </div>
    <div class="col-span-1">
        @if($item->weight)
            <x-dhl::diamentions value="{{$item->weight}}" label="Weight" model="item.weight"/>
        @endif
    </div>
    <div class="col-span-3 flex gap-2">
        @if($item->width)
            <x-dhl::diamentions value="{{$item->width}}" label="Width" model="item.width"/>
        @endif
        @if($item->height)
            <x-dhl::diamentions value="{{$item->height}}" label="Height" model="item.height"/>
        @endif
        @if($item->length)
            <x-dhl::diamentions value="{{$item->length}}" label="Length" model="item.length"/>
        @endif
    </div>
    <div class="col-span-1">
        @if(!is_null($item->nonStandard))
            <x-dhl::non-standard value="{{$item->nonStandard}}" label="Non-stan."/>
        @endif
    </div>
    <div class="col-span-1">
        <x-p::button
            type="button"
            size="small"
            color="danger"
            wire:click="delete"
        >
            Delete {{$index}}
        </x-p::button>
    </div>
    <div class="col-span-12">
        @dump($item)
    </div>
</div>
