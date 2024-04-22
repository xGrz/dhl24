@extends('p::app')

@section('content')
    <x-p::pagination.info :source="$shipments"/>
    <x-p::paper class="bg-slate-800">
        <x-p::paper-title title="Shipment list"/>
        @if($shipments)
            <x-p::table>
                <x-p::table.head>
                    <x-p::table.row>
                        <x-p::table.th>Shipment number</x-p::table.th>
                        <x-p::table.th>Sender</x-p::table.th>
                        <x-p::table.th>Receiver</x-p::table.th>
                        <x-p::table.th>Items</x-p::table.th>
                        <x-p::table.th>Content</x-p::table.th>
                        <x-p::table.th>COD</x-p::table.th>
                    </x-p::table.row>
                </x-p::table.head>
                <x-p::table.body>
                    @foreach($shipments as $shipment)
                        <x-p::table.row>
                            <x-p::table.cell>{{ $shipment->shipment_id }}</x-p::table.cell>
                            <x-p::table.cell>
                                {{ $shipment->shipper['name'] }}<br/>
                                {{ $shipment->shipper['postalCode'] }} {{ $shipment->shipper['city'] }}
                            </x-p::table.cell>
                            <x-p::table.cell>
                                {{ $shipment->receiver['name'] }}<br/>
                                {{ $shipment->receiver['postalCode'] }} {{ $shipment->receiver['city'] }}
                            </x-p::table.cell>
                            <x-p::table.cell>{{ $shipment->items }}</x-p::table.cell>
                            <x-p::table.cell>{{ $shipment->content }}</x-p::table.cell>
                            <x-p::table.cell>{{ $shipment->cod }}</x-p::table.cell>
                        </x-p::table.row>
                    @endforeach
                </x-p::table.body>
            </x-p::table.tbody>

            <div class="py-3">
                <x-p::pagination :source="$shipments"/>
            </div>
        @else
            <x-p::not-found message="Transactions for found."/>
        @endif

    </x-p::paper>
    <x-p::pagination.info :source="$shipments"/>
@endsection
