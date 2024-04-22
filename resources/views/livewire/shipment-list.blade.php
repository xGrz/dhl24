<div>
    <div class="text-right mr-2 mt-3">
        <x-p::button color="success" type="button" wire:click="addPackage()">Add package</x-p::button>
    </div>
    @if($items)
        <x-p::table>
            <x-p::table.head>
                <x-p::table.row>
                    <x-p::table.th></x-p::table.th>
                    <x-p::table.th>Type</x-p::table.th>
                    <x-p::table.th>Quantity</x-p::table.th>
                    <x-p::table.th>Diamentions</x-p::table.th>
                    <x-p::table.th>N/S</x-p::table.th>
                    <x-p::table.th>Actions</x-p::table.th>
                </x-p::table.row>
            </x-p::table.head>
            <x-p::table.body>
                @foreach($items as $key => $item)
                    <livewire:shipment-item wire:key="item_{{$key}}" index="{{$key}}"/>
                @endforeach
            </x-p::table.body>
        </x-p::table>
    @else
        <x-p::not-found message="Packages not found"/>
    @endif
</div>

