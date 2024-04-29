<section>
    <section id="services">
        @if(!empty($services))
            @foreach($services as $serviceName => $isAvailable)
                @if ($isAvailable)
                    <label>
                        <input
                            type="radio"
                            name="deliveryService"
                            value="{{$serviceName}}"
                            wire:model.live="deliveryService"
                            @if(!$isAvailable) disabled @endif

                        />
                        <spam @if(!$isAvailable) class="text-slate-600" @endif>
                            {{$serviceName}}
                        </spam>

                    </label>
                @endif
            @endforeach
        @endif
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-2">

        <section id="value">
            <x-p-textinput label="{{__('dhl::shipment.services.value')}}" type="number" model="value" class="text-right" v="{{$value}}"/>
        </section>
        <section id="cod">
            <x-p-textinput label="{{__('dhl::shipment.services.cod')}}" type="float" model="cod" class="text-right" v="{{$value}}"/>
        </section>
        <section id="reference">
            <x-p::input label="{{__('dhl::shipment.services.reference')}}"/>
        </section>
        <section id="pdi">
            <x-p::switch label="{{__('dhl::shipment.services.pdi')}}" model="pdi" value="{{$pdi}}"/>
        </section>
        <section id="rod">
            <x-p::switch label="{{__('dhl::shipment.services.rod')}}" model="rod" value="{{$rod}}"/>
        </section>
        <section id="owl">
            <x-p::switch label="{{__('dhl::shipment.services.owl')}}" model="owl" value="{{$owl}}"/>
        </section>
        <section id="content">
            <x-p-textinput label="{{__('dhl::shipment.services.content')}}" model="content" :suggestions="$contentSuggestions"/>
        </section>
        <section id="comment">
            <x-p-textinput label="{{__('dhl::shipment.services.comment')}}"/>
        </section>
        <section id="mpk">
            <x-p::select label="{{__('dhl::shipment.services.costsCenter')}}" model="costCenterName">
                @foreach($costsCenter as $costCenterName)
                    <option value="{{$costCenterName}}">{{$costCenterName}}</option>
                @endforeach
            </x-p::select>
        </section>
    </div>
</section>
