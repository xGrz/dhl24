@extends('p::app')

@section('content')
    <x-p-pagination :source="$bookings"/>
    <x-p-paper class="bg-slate-800">
        <x-slot:title>Item list</x-slot:title>
        <x-slot:actions>
            <x-p-button color="success">New courier booking</x-p-button>
        </x-slot:actions>
        @if($bookings)
            <x-p-table>
                <x-p-thead>
                    <x-p-tr>
                        <x-p-th>Booking ID</x-p-th>
                        <x-p-th>Pickup from</x-p-th>
                        <x-p-th>Pickup to</x-p-th>
                        <x-p-th>Info</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($bookings as $booking)
                        <x-p-tr>
                            <x-p-td>{{ $booking->order_id }}</x-p-td>
                            <x-p-td>{{ $booking->pickup_from }}</x-p-td>
                            <x-p-td>{{ $booking->pickup_to }}</x-p-td>
                            <x-p-td>{{ $booking->additional_info }}</x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>

            <div class="py-3">
                <x-p-pagination :source="$bookings"/>
            </div>
        @else
            <x-p-not-found message="Items not found."/>
        @endif

    </x-p-paper>
    <x-p-pagination :source="$bookings"/>
@endsection
