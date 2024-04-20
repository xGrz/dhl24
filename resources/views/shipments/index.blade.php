@extends('dhl::app')

@section('content')
    <x-payu::pagination.info :source="$shipments"/>
    <x-dhl::paper class="bg-slate-800">
        <x-dhl::paper-title title="Shipment list"/>
        @if($shipments)
            <x-dhl::table>
                <x-dhl::table.thead>
                    <x-dhl::table.row>
                        <x-dhl::table.header>Shipment number</x-dhl::table.header>
                        <x-dhl::table.header>Sender</x-dhl::table.header>
                        <x-dhl::table.header>Receiver</x-dhl::table.header>
                        <x-dhl::table.header>Items</x-dhl::table.header>
                        <x-dhl::table.header>Content</x-dhl::table.header>
                        <x-dhl::table.header>COD</x-dhl::table.header>
                    </x-dhl::table.row>
                </x-dhl::table.thead>
                <tbody>
                @foreach($shipments as $shipment)
                    <x-dhl::table.row>
                        <x-dhl::table.cell>{{ $shipment->shipment_id }}</x-dhl::table.cell>
                        <x-dhl::table.cell>
                            {{ $shipment->shipper['name'] }}<br/>
                            {{ $shipment->shipper['postalCode'] }} {{ $shipment->shipper['city'] }}
                        </x-dhl::table.cell>
                        <x-dhl::table.cell>
                            {{ $shipment->receiver['name'] }}<br/>
                            {{ $shipment->receiver['postalCode'] }} {{ $shipment->receiver['city'] }}
                        </x-dhl::table.cell>
                        <x-dhl::table.cell>{{ $shipment->items }}</x-dhl::table.cell>
                        <x-dhl::table.cell>{{ $shipment->content }}</x-dhl::table.cell>
                        <x-dhl::table.cell>{{ $shipment->cod }}</x-dhl::table.cell>
                    </x-dhl::table.row>
                @endforeach
                </tbody>
            </x-dhl::table>

            <div class="py-3">
                <x-payu::pagination :source="$shipments"/>
            </div>
        @else
            <x-payu::not-found message="Transactions for found."/>
        @endif


    </x-dhl::paper>
    <x-payu::pagination.info :source="$shipments"/>
@endsection
