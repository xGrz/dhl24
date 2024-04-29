<section class="flex-1 grid grid-cols-8 gap-x-3 gap-y-2 items-center py-4 text-slate-700 mx-4">
    <div class="col-span-5 lg:col-span-2">
        <div class="text-center text-xs uppercase bg-slate-400 text-slate-700 font-bold rounded-t-md">
            {{ __('dhl::shipment.attributes.type') }}
        </div>
        <select
            wire:model.live.debounce="items.{{$id}}.type"
            class="px-2 py-1 w-full text-slate-800 bg-slate-300 focus:bg-gray-100 rounded-b-md"
        >
            @foreach($shipmentTypes as $shipmentType)
                <option value="{{$shipmentType->name}}">{{$shipmentType->getLabel()}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-span-2 lg:col-span-1">
        <x-dhl::diamentions label="{{__('dhl::shipment.attributes.quantity')}}" model="items.{{$id}}.quantity"/>
    </div>
    <div class="col-span-1 lg:order-1 flex flex-1 place-items-center justify-end">
        <button
            type="button"
            wire:click="removePackage({{$id}})"
            class="block p-1 bg-red-500 hover:bg-red-700 text-white rounded-md text-center"
        >
            <x-p::icons.close class="w-8 h-8"/>
        </button>
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(isset($item['weight']))
            <x-dhl::diamentions label="{{__('dhl::shipment.attributes.weight')}}" model="items.{{$id}}.weight"/>
        @endif
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(isset($item['width']))
            <x-dhl::diamentions label="{{__('dhl::shipment.attributes.width')}}" model="items.{{$id}}.width"/>
        @endif
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(isset($item['length']))
            <x-dhl::diamentions label="{{__('dhl::shipment.attributes.length')}}" model="items.{{$id}}.length"/>
        @endif
    </div>
    <div class="col-span-2 lg:col-span-1">
        @if(isset($item['height']))
            <x-dhl::diamentions label="{{__('dhl::shipment.attributes.height')}}" model="items.{{$id}}.height"/>
        @endif
    </div>
    <div class="col-span-4 order-2">
        @if(isset($item['nonStandard']))
            <x-dhl::non-standard
                label="{{__('dhl::shipment.attributes.nonStandard')}}"
                model="items.{{$id}}.nonStandard"
                value="{{$item['nonStandard']}}"
                shouldBeNonStandard="{{$item['shouldBeNonStandard'] ?? false}}"
            />
        @endif
    </div>
</section>
