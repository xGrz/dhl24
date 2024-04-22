@extends('p::app')

@section('content')
    <x-p::pagination.info :source="$bookings"/>
    <x-p::paper class="bg-slate-800">
        <x-p::paper-title title="Item list"/>
        @if($bookings)
            <x-p::table>
                <x-p::table.head>
                    <x-p::table.row>
                        <x-p::table.th>Booking ID</x-p::table.th>
                        <x-p::table.th>Pickup from</x-p::table.th>
                        <x-p::table.th>Pickup to</x-p::table.th>
                        <x-p::table.th>Info</x-p::table.th>
                    </x-p::table.row>
                </x-p::table.head>
                <x-p::table.body>
                    @foreach($bookings as $booking)
                        <x-p::table.row>
                            <x-p::table.cell>{{ $booking->order_id }}</x-p::table.cell>
                            <x-p::table.cell>{{ $booking->pickup_from }}</x-p::table.cell>
                            <x-p::table.cell>{{ $booking->pickup_to }}</x-p::table.cell>
                            <x-p::table.cell>{{ $booking->additional_info }}</x-p::table.cell>
                        </x-p::table.row>
                    @endforeach
                </x-p::table.body>
            </x-p::table.tbody>

            <div class="py-3">
                <x-p::pagination :source="$bookings"/>
            </div>
        @else
            <x-p::not-found message="Items not found."/>
        @endif

    </x-p::paper>
    <x-p::pagination.info :source="$bookings"/>
@endsection
