<section class="flex-1 grid grid-cols-8 gap-x-3 gap-y-2 items-center py-4 text-slate-700 mx-4">
    <div class="col-span-5 lg:col-span-2">
        <div class="text-center text-xs uppercase bg-slate-400 text-slate-700 font-bold rounded-t-md">
            Shipment type
        </div>
        <select
            wire:model.live.debounce.200ms="type"
            class="px-2 py-1 w-full text-slate-800 bg-slate-300 focus:bg-gray-100 rounded-b-md"
        >
            @foreach($shipmentTypes as $shipmentType)
                <option value="{{$shipmentType->name}}">{{$shipmentType->value}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-span-2 lg:col-span-1">
        <x-dhl::diamentions value="{{$quantity}}" label="Quantity" model="quantity"/>
    </div>
    <div class="col-span-1 lg:order-1 flex flex-1 place-items-center justify-end">
        <button
            type="button"
            wire:click="delete"
            class="block p-1 bg-red-500 hover:bg-red-700 text-white rounded-md text-center"
        >
            <x-p::icons.close class="w-8 h-8"/>
        </button>
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(!is_null($weight))
            <x-dhl::diamentions value="{{$weight}}" label="Weight" model="weight"/>
        @endif
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(!is_null($width))
            <x-dhl::diamentions value="{{$width}}" label="Width" model="width"/>
        @endif
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(!is_null($height))
            <x-dhl::diamentions value="{{$height}}" label="Height" model="height"/>
        @endif
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(!is_null($length))
            <x-dhl::diamentions value="{{$length}}" label="Length" model="length"/>
        @endif
    </div>

    <div class="col-span-8 order-2">
        @if(!is_null($nonStandard))
            <x-dhl::non-standard
                value="{{$nonStandard}}"
                label="Non standard"
                shouldBeNonStandard="{{$shouldBeNonStandard}}"
            />
        @endif
    </div>

</section>

