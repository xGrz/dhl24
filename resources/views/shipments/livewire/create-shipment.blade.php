<div>
    <form wire:submit="createPackage">
        {{-- ADDRESS --}}
        <x-p-paper class="mb-4">
            <x-slot:title>Create shipment</x-slot:title>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mx-2">
                <div class="col-span-2">
                    <h2>Recipient address:</h2>
                    <div class="grid grid-cols-12 gap-x-4 gap-y-1 mx-2">
                        <div class="col-span-12">
                            <x-dhl::input label="Recipient full name" model="recipient.name"/>
                        </div>
                        <div class="col-span-4">
                            <x-dhl::input label="Postal code" model="recipient.postalCode"/>
                        </div>
                        <div class="col-span-8">
                            <x-dhl::input label="City" model="recipient.city"/>
                        </div>
                        <div class="col-span-8">
                            <x-dhl::input label="Street" model="recipient.street"/>
                        </div>
                        <div class="col-span-4">
                            <x-dhl::input label="House number" model="recipient.houseNumber"/>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 lg:col-span-1">
                    <h2>Contact</h2>
                    <div class="grid gap-x-4 gap-y-1 mx-2">
                        <div class="col-span-12">
                            <x-dhl::input label="Contact name" model="contact.name" lazy/>
                        </div>
                        <div class="col-span-12">
                            <x-dhl::input label="Contact email" model="contact.email" lazy/>
                        </div>
                        <div class="col-span-12">
                            <x-dhl::input label="Contact phone" model="contact.phone" lazy/>
                        </div>
                    </div>
                </div>
            </div>

        </x-p-paper>

        {{-- ITEMS --}}
        <x-p-paper class="mb-4">
            <x-slot:title>Packages</x-slot:title>
            <x-slot:actions>
                <button type="button" class="text-green-500" wire:click="addItem()">
                    <x-p::icons.add-circle/>
                </button>

            </x-slot:actions>
            @if($items)
                @foreach($items as $key => $item)
                    <div wire:key="items_{{$key}}">
                        <x-dhl::shipment-item id="{{$key}}" :$shipmentTypes :$item/>
                    </div>
                @endforeach
            @else
                <x-p-not-found message="Packages not found"/>
            @endif

            <div class="text-center mr-2 mt-3 pt-1 pb-4">
                <x-p-button type="button" wire:click.prevent="addItem()">
                    Add package
                </x-p-button>
            </div>

        </x-p-paper>

        {{-- SERVICES --}}
        <x-p-paper class="mb-4">
            <x-slot:title>Services</x-slot:title>
            <x-p-select wire:model.live="services.deliveryService" label="Rodzaj przesyłki">
                @foreach($deliveryTypes as $type)
                    <option value="{{$type['symbol']}}">{{$type['label']}}</option>
                @endforeach
            </x-p-select>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                <div>
                    <x-p-input
                        label="{{__('dhl::shipment.services.content')}}"
                        wire:model.live.debounce="services.content"
                        :suggestions="$services->contentSuggestions"
                    />
                    <x-p-select label="{{__('dhl::shipment.services.costsCenter')}}"
                                wire:model="services.costCenterName">
                        @foreach($services->costsCenter as $costCenterName)
                            <option value="{{$costCenterName}}">{{$costCenterName}}</option>
                        @endforeach
                    </x-p-select>
                    <x-p-input label="{{__('dhl::shipment.services.comment')}}"
                               wire:model.live.debounce="services.comment"/>
                </div>
                <div>
                    <x-p-input
                        label="{{__('dhl::shipment.services.value')}}"
                        type="float"
                        wire:model.live.debounce="services.value"
                        class="text-right"
                    />
                    <x-p-input
                        label="{{__('dhl::shipment.services.cod')}}"
                        type="float"
                        wire:model.live.debounce="services.cod"
                        class="text-right"
                    />
                    <x-p-input
                        label="{{__('dhl::shipment.services.reference')}}"
                        wire:model.live="services.references"
                    />
                </div>
                <div>
                    <div class="text-sm">Usługi dodatkowe</div>
                    <x-p-checkbox as-switch label="{{__('dhl::shipment.services.pdi') }}" wire:model.live="services.pdi"/>
                    <x-p-checkbox as-switch label="{{__('dhl::shipment.services.rod')}}" wire:model.live="services.rod"/>
                    <x-p-checkbox as-switch label="{{__('dhl::shipment.services.owl')}}" wire:model.live="services.owl"/>
                </div>
            </div>
        </x-p-paper>


        {{-- SUBMIT --}}
        <div class="px-2 py-4 text-center">
            <x-p-button type="submit">Utwórz przesyłkę</x-p-button>
        </div>
    </form>
</div>
