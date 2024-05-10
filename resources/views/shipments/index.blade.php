@extends('p::app')

@section('content')
    <x-p-pagination :source="$shipments"/>
    <x-p-paper>
        <x-slot:title>Shipment list</x-slot:title>
        <x-slot:actions>
            <x-p-button href="{{ route('dhl24.shipments.create') }}" color="success">Create</x-p-button>
        </x-slot:actions>
        @if($shipments)
            <x-p-table>
                <x-p-thead>
                    <x-p-tr>
                        <x-p-th>Shipment number</x-p-th>
                        <x-p-th>Sender</x-p-th>
                        <x-p-th>Receiver</x-p-th>
                        <x-p-th>Items</x-p-th>
                        <x-p-th>Content</x-p-th>
                        <x-p-th>COD</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($shipments as $shipment)
                        <x-p-tr>
                            <x-p-td>{{ $shipment->shipment_id }}</x-p-td>
                            <x-p-td>
                                {{ $shipment->shipper['name'] }}<br/>
                                {{ $shipment->shipper['postalCode'] }} {{ $shipment->shipper['city'] }}
                            </x-p-td>
                            <x-p-td>
                                {{ $shipment->receiver['name'] }}<br/>
                                {{ $shipment->receiver['postalCode'] }} {{ $shipment->receiver['city'] }}
                            </x-p-td>
                            <x-p-td center>{{ $shipment->items }}</x-p-td>
                            <x-p-td>{{ $shipment->content }}</x-p-td>
                            <x-p-td right>{{ $shipment->cod }}</x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>

            <div class="py-3">
                <x-p-pagination :source="$shipments"/>
            </div>
        @else
            <x-p-not-found message="Transactions for found."/>
        @endif

    </x-p-paper>
    <x-p-pagination :source="$shipments"/>
@endsection
