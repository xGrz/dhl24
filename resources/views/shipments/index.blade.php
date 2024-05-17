@extends('p::app')

@section('content')
    <x-p-pagination :source="$shipments"/>
    <x-p-paper>
        <x-slot:title>ShipmentDraft list</x-slot:title>
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
                        <x-p-th>COD</x-p-th>
                        <x-p-th>Cost</x-p-th>
                        <x-p-th>Options</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($shipments as $shipment)
                        <x-p-tr>
                            <x-p-td>
                                <x-p-link href="{{route('dhl24.shipments.show', $shipment->id)}}">
                                    {{ $shipment->number }}
                                </x-p-link>
                            </x-p-td>
                            <x-p-td>
                                {{ $shipment->shipper_name }}
                            </x-p-td>
                            <x-p-td>
                                {{ $shipment->receiver_name }}<br/>
                                {{ $shipment->receiver_postal_code }} {{ $shipment->receiver_city }}
                            </x-p-td>
                            <x-p-td center>{{ $shipment->items->count() }}</x-p-td>
                            <x-p-td right>{{ $shipment->collect_on_delivery }}</x-p-td>
                            <x-p-td right>
                                {{ $shipment->cost }}
                                <div class="text-xs">{{ $shipment->cost_center->name }}</div>
                            </x-p-td>
                            <x-p-td right>
                                <x-p-button href="{{ route('dhl24.shipments.label', $shipment->id) }}" size="small">
                                    Label
                                </x-p-button>
                                <x-p-button href="{{ route('dhl24.shipments.destroy', $shipment->id) }}" size="small" color="danger">
                                    Delete
                                </x-p-button>
                            </x-p-td>
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
