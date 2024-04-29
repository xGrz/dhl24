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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
        <div>
            <x-p-textinput label="{{__('dhl::shipment.services.content')}}" model="content"
                           :suggestions="$contentSuggestions"/>
            <section id="mpk">
                <x-p::select label="{{__('dhl::shipment.services.costsCenter')}}" model="costCenterName">
                    @foreach($costsCenter as $costCenterName)
                        <option value="{{$costCenterName}}">{{$costCenterName}}</option>
                    @endforeach
                </x-p::select>
            </section>
            <x-p-textinput label="{{__('dhl::shipment.services.comment')}}"/>
        </div>
        <div>
            <x-p-textinput label="{{__('dhl::shipment.services.value')}}" type="number" model="value" class="text-right"
                           v="{{$value}}"/>

            <x-p-textinput label="{{__('dhl::shipment.services.cod')}}" type="float" model="cod" class="text-right"
                           v="{{$value}}"/>
            <x-p::input label="{{__('dhl::shipment.services.reference')}}"/>
        </div>
        <div>
            <div class="text-sm">Usługi dodatkowe</div>
            <x-p::switch label="{{__('dhl::shipment.services.pdi')}}" model="pdi" value="{{$pdi}}"/>
            <x-p::switch label="{{__('dhl::shipment.services.rod')}}" model="rod" value="{{$rod}}"/>
            <x-p::switch label="{{__('dhl::shipment.services.owl')}}" model="owl" value="{{$owl}}"/>
        </div>
    </div>
</section>
