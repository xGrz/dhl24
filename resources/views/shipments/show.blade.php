@extends('p::app')

@section('content')
    <div class="grid gap-2">
        {{ $shipment->tracking->first()->symbol }}
        <div class="text-right">
            <x-p-button href="{{route('dhl24.shipments.label', $shipment->id)}}">
                Label
            </x-p-button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <x-p-paper>
                <x-slot:title>Receiver</x-slot:title>
                <div>{{$shipment->receiver_name}}</div>
                <div>{{$shipment->receiver_contact_person}}</div>
                <div>{{$shipment->receiver_postal_code}} {{$shipment->receiver_city}}</div>
                <div>{{$shipment->receiver_street}} {{$shipment->receiver_house_number}}</div>
                @if ($shipment->receiver_contact_email || $shipment->receiver_contact_phone)
                    <section class="mt-2">
                        <table>
                            @if($shipment->receiver_contact_phone)
                                <tr>
                                    <td class="pr-4">phone</td>
                                    <td>{{$shipment->receiver_contact_phone}}</td>
                                </tr>
                            @endif
                            @if($shipment->receiver_contact_email)
                                <tr>
                                    <td class="pr-4">email</td>
                                    <td>
                                        <x-p-link href="mailto:{{$shipment->receiver_contact_email}}">
                                            {{$shipment->receiver_contact_email}}
                                        </x-p-link>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </section>
                @endif
            </x-p-paper>
            <x-p-paper>
                <x-slot:title>Sender</x-slot:title>
                <div>{{$shipment->shipper_name}}</div>
                <div>{{$shipment->shipper_contact_person}}</div>
                <div>{{$shipment->shipper_postal_code}} {{$shipment->shipper_city}}</div>
                <div>{{$shipment->shipper_street}} {{$shipment->shipper_house_number}}</div>
                @if ($shipment->shipper_contact_email || $shipment->shipper_contact_phone)
                    <section class="mt-2">
                        <table>
                            @if($shipment->shipper_contact_phone)
                                <tr>
                                    <td class="pr-4">phone</td>
                                    <td>{{$shipment->shipper_contact_phone}}</td>
                                </tr>
                            @endif
                            @if($shipment->shipper_contact_email)
                                <tr>
                                    <td class="pr-4">email</td>
                                    <td>
                                        <x-p-link href="mailto:{{$shipment->shipper_contact_email}}">
                                            {{$shipment->shipper_contact_email}}
                                        </x-p-link>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </section>
                @endif
            </x-p-paper>
        </div>
        <x-p-paper>
            <x-slot:title>Package items</x-slot:title>
            <x-p-table size="small" highlight="false">
                <x-p-tbody>
                    @foreach($shipment->items as $item)
                        <x-p-tr>
                            <x-p-td>{{$item->type}}</x-p-td>
                            <x-p-td right>{{$item->quantity}} pcs</x-p-td>
                            <x-p-td right>{{$item->getWeight()}}</x-p-td>
                            <x-p-td right>{{$item->getDiamentions()}}</x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>
            <div class="max-w-md ms-auto">
                <x-p-table size="small" highlight="false" class="mt-2">
                    <x-p-tbody>
                        <x-p-tr>
                            <x-p-td>COD:</x-p-td>
                            <x-p-td right>{{ $shipment->collect_on_delivery }}</x-p-td>
                        </x-p-tr>
                        <x-p-tr>
                            <x-p-td>COD Reference:</x-p-td>
                            <x-p-td right>{{$shipment->collect_on_delivery_reference}}</x-p-td>
                        </x-p-tr>
                        <x-p-tr>
                            <x-p-td>Insurance:</x-p-td>
                            <x-p-td right>{{ $shipment->insurance }}</x-p-td>
                        </x-p-tr>
                    </x-p-tbody>
                </x-p-table>
            </div>
        </x-p-paper>
        <x-p-paper>
            <x-slot:title>Details</x-slot:title>
            <div class="grid gap-2 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Shipment date</x-slot:name>
                    <x-slot:value>{{ $shipment->shipment_date?->format('d-m-Y') }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Shipment type</x-slot:name>
                    <x-slot:value>{{ $shipment->product->name }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip class="md:col-span-2">
                    <x-slot:name>Shipment content</x-slot:name>
                    <x-slot:value>{{ $shipment->content }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Cost center</x-slot:name>
                    <x-slot:value>{{ $shipment->cost_center?->name }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Reference</x-slot:name>
                    <x-slot:value>{{ $shipment->reference }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Return on delivery</x-slot:name>
                    <x-slot:value>{{ $shipment->return_on_delivery ? 'Yes' : false}}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Real cost</x-slot:name>
                    <x-slot:value>{{ $shipment->cost }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Proof of delivery</x-slot:name>
                    <x-slot:value>{{ $shipment->proof_of_delivery ? 'Yes' : false }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Self collect</x-slot:name>
                    <x-slot:value>{{ $shipment->self_collect ? 'Yes' : false }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>PDI</x-slot:name>
                    <x-slot:value>{{ $shipment->predelivery_information ? 'Yes' : false }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Pre aviso</x-slot:name>
                    <x-slot:value>{{ $shipment->preaviso? 'Yes' : false }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Payer</x-slot:name>
                    <x-slot:value>{{ $shipment->payer_type }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip>
                    <x-slot:name>Courier booking id</x-slot:name>
                    <x-slot:value>{{ $shipment->courier_booking?->order_id }}</x-slot:value>
                </x-dhl::shipment-detail-chip>
                <x-dhl::shipment-detail-chip class="md:col-span-2 md:col-span-2 lg:col-span-4 xl:md-col-span-6">
                    <x-slot:name>Comment</x-slot:name>
                    <x-slot:value class="!text-left">{{ $shipment->comment }}</x-slot:value>
                </x-dhl::shipment-detail-chip>

            </div>
        </x-p-paper>
        <x-p-paper>
            <x-slot:title>Tracking</x-slot:title>
            <ol class="relative border-s border-gray-500">
                @foreach($shipment->tracking as $event)
                    <li class="mb-4 ms-4">
                        <div class="absolute w-3 h-3 bg-red-800 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$event->pivot->event_timestamp}}</time>
                        <div class="text-lg font-semibold text-white">{{$event->description}}</div>
                        <p>{{'@'}}{{$event->pivot->terminal}}</p>
                    </li>
                @endforeach
            </ol>

        </x-p-paper>
    </div>
@endsection
